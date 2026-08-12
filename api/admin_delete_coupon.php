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

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
    exit;
}

try {
    $pdo = get_db_connection();
    
    $stmt = $pdo->prepare("DELETE FROM coupons WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Cupom deletado com sucesso!'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
