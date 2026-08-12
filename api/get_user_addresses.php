<?php
// api/get_user_addresses.php - Busca todos os endereços salvos do cliente logado
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Você precisa estar logado para consultar endereços.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = get_db_connection();
    $userId = $_SESSION['user']['id'];

    $stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = :user_id ORDER BY is_default DESC, id DESC");
    $stmt->execute([':user_id' => $userId]);
    $addresses = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'data' => $addresses
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
