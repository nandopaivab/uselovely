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

try {
    $pdo = get_db_connection();

    $publicKey = $input['publicKey'] ?? '';
    $accessToken = $input['accessToken'] ?? '';

    // Insert or replace config values (MySQL & SQLite compatible)
    $stmt = $pdo->prepare("DELETE FROM site_config WHERE config_key IN ('mp_public_key', 'mp_access_token')");
    $stmt->execute();

    $stmt = $pdo->prepare("INSERT INTO site_config (config_key, config_value) VALUES (:key, :val)");
    $stmt->execute([':key' => 'mp_public_key', ':val' => $publicKey]);
    $stmt->execute([':key' => 'mp_access_token', ':val' => $accessToken]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Configurações do Mercado Pago salvas no banco local com sucesso.'
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
