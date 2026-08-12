<?php
// api/delete_user_address.php - Exclui um endereço salvo do cliente
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
    echo json_encode(['status' => 'error', 'message' => 'Você precisa estar logado para remover endereços.']);
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

    $stmt = $pdo->prepare("DELETE FROM user_addresses WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $addressId, ':user_id' => $userId]);

    // Se sobrou algum endereço sem padrão, torna o primeiro como padrão
    $chk = $pdo->prepare("SELECT COUNT(*) FROM user_addresses WHERE user_id = :user_id AND is_default = 1");
    $chk->execute([':user_id' => $userId]);
    if ($chk->fetchColumn() == 0) {
        $setFirst = $pdo->prepare("UPDATE user_addresses SET is_default = 1 WHERE user_id = :user_id LIMIT 1");
        $setFirst->execute([':user_id' => $userId]);
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Endereço removido com sucesso!'
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
