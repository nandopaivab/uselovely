<?php
// api/update_customer_profile.php - Atualização dos dados de perfil do cliente
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Você precisa estar logado para atualizar seu perfil.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$userId = $_SESSION['user']['id'];
$name = trim($input['name'] ?? '');
$phone = trim($input['phone'] ?? '');
$cpf = trim($input['cpf'] ?? '');
$newPassword = $input['new_password'] ?? '';

if (empty($name)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'O nome é obrigatório.']);
    exit;
}

try {
    $pdo = get_db_connection();

    if (!empty($newPassword)) {
        $hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name = :name, phone = :phone, cpf = :cpf, password = :pass WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':cpf' => $cpf,
            ':pass' => $hashedPass,
            ':id' => $userId
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = :name, phone = :phone, cpf = :cpf WHERE id = :id");
        $stmt->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':cpf' => $cpf,
            ':id' => $userId
        ]);
    }

    // Refresh session data
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['cpf'] = $cpf;

    echo json_encode([
        'status' => 'success',
        'message' => 'Seus dados foram atualizados com sucesso!',
        'user' => $_SESSION['user']
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
