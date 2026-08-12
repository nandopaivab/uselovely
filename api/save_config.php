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
    $promoSinglePrice = isset($input['promoSinglePrice']) ? str_replace(',', '.', $input['promoSinglePrice']) : '49.90';
    $promoComboPrice = isset($input['promoComboPrice']) ? str_replace(',', '.', $input['promoComboPrice']) : '99.99';

    // Insert or replace config values (MySQL & SQLite compatible)
    $stmt = $pdo->prepare("DELETE FROM site_config WHERE config_key IN ('mp_public_key', 'mp_access_token', 'promo_single_price', 'promo_combo_price')");
    $stmt->execute();

    $stmt = $pdo->prepare("INSERT INTO site_config (config_key, config_value) VALUES (:key, :val)");
    $stmt->execute([':key' => 'mp_public_key', ':val' => $publicKey]);
    $stmt->execute([':key' => 'mp_access_token', ':val' => $accessToken]);
    $stmt->execute([':key' => 'promo_single_price', ':val' => $promoSinglePrice]);
    $stmt->execute([':key' => 'promo_combo_price', ':val' => $promoComboPrice]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Configurações salvas no banco com sucesso.'
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
