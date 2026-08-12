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

if (!$input || empty($input['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID do pedido é obrigatório.']);
    exit;
}

try {
    $pdo = get_db_connection();

    $id = $input['id'];
    $status = $input['status'] ?? 'Em Separação';
    $trackingCode = $input['trackingCode'] ?? '';

    $stmt = $pdo->prepare("UPDATE orders SET status = :status, tracking_code = :trackingCode WHERE id = :id");
    $stmt->execute([
        ':status' => $status,
        ':trackingCode' => $trackingCode,
        ':id' => $id
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Status do pedido atualizado com sucesso.'], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
