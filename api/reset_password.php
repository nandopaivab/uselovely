<?php
ini_set('display_errors', '0');
error_reporting(E_ALL);
ob_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    ob_end_clean();
    exit(0);
}

require_once __DIR__ . '/../config/database.php';

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $token = trim($input['token'] ?? '');
    $password = $input['password'] ?? '';

    if (empty($token) || empty($password)) {
        http_response_code(400);
        ob_end_clean();
        echo json_encode(['status' => 'error', 'message' => 'Token e nova senha são obrigatórios.']);
        exit;
    }

    $pdo = get_db_connection();

    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = :token AND reset_expires > NOW()");
    $stmt->execute([':token' => $token]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(400);
        ob_end_clean();
        echo json_encode(['status' => 'error', 'message' => 'Token inválido ou expirado.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id");
    $stmt->execute([
        ':password' => $hashedPassword,
        ':id' => $user['id']
    ]);

    ob_end_clean();
    echo json_encode([
        'status' => 'success',
        'message' => 'Senha alterada com sucesso. Você já pode fazer login.'
    ], JSON_UNESCAPED_UNICODE);

} catch (\Throwable $e) {
    ob_end_clean();
    error_log("Erro no reset_password: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro interno do servidor. Tente novamente mais tarde.']);
}

