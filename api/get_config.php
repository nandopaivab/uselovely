<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = get_db_connection();
    $stmt = $pdo->query("SELECT * FROM site_config");
    $rows = $stmt->fetchAll();

    $config = [];
    foreach ($rows as $r) {
        $config[$r['config_key']] = $r['config_value'];
    }

    echo json_encode([
        'status' => 'success',
        'data' => [
            'publicKey' => $config['mp_public_key'] ?? '',
            'accessToken' => $config['mp_access_token'] ?? ''
        ]
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
