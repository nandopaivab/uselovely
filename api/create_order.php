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

if (!$input) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dados do pedido ausentes.']);
    exit;
}

try {
    $pdo = get_db_connection();

    $orderId = '#LV-' . rand(10000, 99999);
    $email = $input['email'] ?? 'cliente@uselovely.com';
    $name = $input['name'] ?? 'Cliente';
    $phone = $input['phone'] ?? '';
    $cep = $input['address']['cep'] ?? '';
    $street = $input['address']['street'] ?? '';
    $number = $input['address']['number'] ?? '';
    $complement = $input['address']['complement'] ?? '';
    $neighborhood = $input['address']['neighborhood'] ?? '';
    $city = $input['address']['city'] ?? '';
    $state = $input['address']['state'] ?? '';
    $paymentMethod = $input['paymentMethod'] ?? 'PIX';
    $totalAmount = (float)($input['totalAmount'] ?? 0.0);
    $itemsJson = json_encode($input['items'] ?? [], JSON_UNESCAPED_UNICODE);

    $sql = "INSERT INTO orders (
        id, user_email, user_name, user_phone, address_cep, address_street, address_number,
        address_complement, address_neighborhood, address_city, address_state,
        payment_method, total_amount, order_items, status
    ) VALUES (
        :id, :email, :name, :phone, :cep, :street, :number,
        :complement, :neighborhood, :city, :state,
        :payment_method, :total_amount, :order_items, 'Em Separação'
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $orderId,
        ':email' => $email,
        ':name' => $name,
        ':phone' => $phone,
        ':cep' => $cep,
        ':street' => $street,
        ':number' => $number,
        ':complement' => $complement,
        ':neighborhood' => $neighborhood,
        ':city' => $city,
        ':state' => $state,
        ':payment_method' => $paymentMethod,
        ':total_amount' => $totalAmount,
        ':order_items' => $itemsJson
    ]);

    echo json_encode([
        'status' => 'success',
        'orderId' => $orderId,
        'message' => 'Pedido gravado no banco de dados com sucesso.'
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
