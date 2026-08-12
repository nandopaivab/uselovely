<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$code = strtoupper(trim($input['code'] ?? ''));
$type = $input['type'] ?? 'percentage'; // percentage or fixed
$value = (float)($input['value'] ?? 0);
$usageLimit = (int)($input['usage_limit'] ?? 1);
$expiresAt = !empty($input['expires_at']) ? $input['expires_at'] : null;
$userEmail = trim($input['user_email'] ?? '');

if (empty($code) || $value <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Código e valor são obrigatórios e devem ser maiores que zero.']);
    exit;
}

try {
    $pdo = get_db_connection();

    // Check if code already exists
    $stmt = $pdo->prepare("SELECT id FROM coupons WHERE code = :code");
    $stmt->execute([':code' => $code]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Este código de cupom já existe.']);
        exit;
    }

    $userId = null;
    if (!empty($userEmail)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $userEmail]);
        $u = $stmt->fetch();
        if ($u) {
            $userId = $u['id'];
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'E-mail de usuário não encontrado.']);
            exit;
        }
    }

    $sql = "INSERT INTO coupons (code, type, value, user_id, usage_limit, expires_at) 
            VALUES (:code, :type, :value, :user_id, :usage_limit, :expires_at)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':code' => $code,
        ':type' => $type,
        ':value' => $value,
        ':user_id' => $userId,
        ':usage_limit' => $usageLimit,
        ':expires_at' => $expiresAt
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Cupom criado com sucesso!'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
