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

$id = (int)($input['id'] ?? 0);
$type = $input['type'] ?? 'percentage';
$value = (float)($input['value'] ?? 0);
$usageLimit = (int)($input['usage_limit'] ?? 1);
$expiresAt = !empty($input['expires_at']) ? $input['expires_at'] : null;
$userEmail = trim($input['user_email'] ?? '');

if ($id <= 0 || $value <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID inválido ou valor menor/igual a zero.']);
    exit;
}

try {
    $pdo = get_db_connection();

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

    $sql = "UPDATE coupons SET 
            type = :type, 
            value = :value, 
            user_id = :user_id, 
            usage_limit = :usage_limit, 
            expires_at = :expires_at 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':type' => $type,
        ':value' => $value,
        ':user_id' => $userId,
        ':usage_limit' => $usageLimit,
        ':expires_at' => $expiresAt,
        ':id' => $id
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Cupom atualizado com sucesso!'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
