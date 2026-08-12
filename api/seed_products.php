<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = get_db_connection();
    $pdo->exec("DELETE FROM products");
    seed_default_products($pdo);

    echo json_encode([
        'status' => 'success',
        'message' => 'Os 5 produtos padrões foram restaurados no banco de dados local com sucesso.'
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
