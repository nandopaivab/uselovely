<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

$email = $_GET['email'] ?? '';

if (empty($email)) {
    echo json_encode(['status' => 'success', 'data' => []]);
    exit;
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_email = :email ORDER BY created_at DESC");
    $stmt->execute([':email' => $email]);
    $rows = $stmt->fetchAll();

    $orders = [];
    foreach ($rows as $r) {
        $orders[] = [
            'id' => $r['id'],
            'userEmail' => $r['user_email'],
            'userName' => $r['user_name'],
            'userPhone' => $r['user_phone'],
            'address' => [
                'cep' => $r['address_cep'],
                'street' => $r['address_street'],
                'number' => $r['address_number'],
                'complement' => $r['address_complement'],
                'neighborhood' => $r['address_neighborhood'],
                'city' => $r['address_city'],
                'state' => $r['address_state']
            ],
            'paymentMethod' => $r['payment_method'],
            'totalAmount' => (float)$r['total_amount'],
            'items' => json_decode($r['order_items'], true),
            'status' => $r['status'],
            'trackingCode' => $r['tracking_code'],
            'createdAt' => $r['created_at']
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $orders], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
