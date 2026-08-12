<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$token = trim($input['token'] ?? '');
$password = $input['password'] ?? '';

if (empty($token) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Token e nova senha são obrigatórios.']);
    exit;
}

try {
    $pdo = get_db_connection();

    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = :token AND reset_expires > NOW()");
    $stmt->execute([':token' => $token]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Token inválido ou expirado.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id");
    $stmt->execute([
        ':password' => $hashedPassword,
        ':id' => $user['id']
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Senha alterada com sucesso. Você já pode fazer login.'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
