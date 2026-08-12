<?php
// api/set_default_address.php - Define o endereço principal/padrão de entrega do cliente
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
    echo json_encode(['status' => 'error', 'message' => 'Você precisa estar logado para alterar o endereço padrão.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$userId = $_SESSION['user']['id'];
$addressId = (int)($input['id'] ?? 0);

if (!$addressId) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID do endereço é obrigatório.']);
    exit;
}

try {
    $pdo = get_db_connection();

    // Reset all addresses to is_default = 0
    $unmark = $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = :user_id");
    $unmark->execute([':user_id' => $userId]);

    // Set target address to is_default = 1
    $mark = $pdo->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = :id AND user_id = :user_id");
    $mark->execute([':id' => $addressId, ':user_id' => $userId]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Endereço padrão de entrega atualizado com sucesso!'
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
