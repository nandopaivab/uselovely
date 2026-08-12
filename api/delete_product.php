<?php
// api/delete_product.php - Endpoint para excluir um produto do MySQL
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'O ID do produto é obrigatório para exclusão.']);
    exit;
}

try {
    $pdo = get_db_connection();
    $id = trim($input['id']);

    // Verificar se o produto existe
    $chk = $pdo->prepare("SELECT name FROM products WHERE id = :id");
    $chk->execute([':id' => $id]);
    $prod = $chk->fetch();

    if (!$prod) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Produto não encontrado no banco de dados.']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode([
        'status' => 'success',
        'message' => "O produto \"{$prod['name']}\" foi excluído com sucesso!"
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
