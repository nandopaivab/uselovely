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
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_email = :email OR user_email = :email ORDER BY created_at DESC");
    $stmt->execute([':email' => $email]);
    $rows = $stmt->fetchAll();

    $orders = [];
    foreach ($rows as $r) {
        $address = json_decode($r['shipping_address'] ?? '{}', true) ?: [];
        $items = json_decode($r['items'] ?? '{}', true) ?: [];

        $orders[] = [
            'id' => $r['id'],
            'orderNumber' => $r['order_number'] ?? $r['id'],
            'customerName' => $r['customer_name'] ?? $r['user_name'] ?? '',
            'customerEmail' => $r['customer_email'] ?? $r['user_email'] ?? '',
            'customerPhone' => $r['customer_phone'] ?? $r['user_phone'] ?? '',
            'address' => $address,
            'items' => $items,
            'totalAmount' => (float)($r['total_amount'] ?? 0.0),
            'paymentMethod' => $r['payment_method'] ?? 'Mercado Pago',
            'paymentStatus' => $r['payment_status'] ?? 'pending',
            'orderStatus' => $r['order_status'] ?? 'awaiting_payment',
            'createdAt' => $r['created_at']
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $orders], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
