<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = get_db_connection();
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
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
            'customerCpf' => $r['customer_cpf'] ?? '',
            'address' => $address,
            'items' => $items,
            'subtotal' => (float)($r['subtotal'] ?? 0.0),
            'shippingAmount' => (float)($r['shipping_amount'] ?? 0.0),
            'discountAmount' => (float)($r['discount_amount'] ?? 0.0),
            'totalAmount' => (float)($r['total_amount'] ?? 0.0),
            'paymentMethod' => $r['payment_method'] ?? 'Mercado Pago',
            'paymentStatus' => $r['payment_status'] ?? 'pending',
            'orderStatus' => $r['order_status'] ?? 'awaiting_payment',
            'preferenceId' => $r['mercado_pago_preference_id'] ?? '',
            'paymentId' => $r['mercado_pago_payment_id'] ?? '',
            'externalReference' => $r['external_reference'] ?? '',
            'stockReduced' => (int)($r['stock_reduced'] ?? 0),
            'createdAt' => $r['created_at']
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $orders], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
