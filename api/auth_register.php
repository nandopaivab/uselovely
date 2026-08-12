<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';
$phone = trim($input['phone'] ?? '');

if (empty($name) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Nome, E-mail e Senha são obrigatórios.']);
    exit;
}

try {
    $pdo = get_db_connection();

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Este e-mail já está cadastrado no MySQL.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (:name, :email, :pass, :phone, 'customer')");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':pass' => $hashedPassword,
        ':phone' => $phone
    ]);

    $userId = $pdo->lastInsertId();

    $sessionData = [
        'id' => $userId,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'role' => 'customer'
    ];

    $_SESSION['user'] = $sessionData;

    echo json_encode([
        'status' => 'success',
        'message' => 'Conta de cliente criada com sucesso no MySQL.',
        'user' => $sessionData
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
