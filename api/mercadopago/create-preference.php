<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../services/mercadopago.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['items']) || empty($input['customer'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos para criação do pedido.']);
    exit;
}

try {
    $pdo = get_db_connection();

    $customer = $input['customer'];
    $address = $input['address'] ?? [];
    $rawItems = $input['items'];

    // 1. RECALCULAR PREÇOS NO BACKEND (SEGURANÇA CONTRA FRAUDE DE PREÇO NO FRONTEND)
    $recalculatedItems = [];
    $mpItems = [];
    $subtotal = 0.00;

    // Buscar todos os produtos do MySQL
    $stmt = $pdo->query("SELECT * FROM products");
    $dbProducts = [];
    foreach ($stmt->fetchAll() as $p) {
        $dbProducts[$p['id']] = $p;
    }

    foreach ($rawItems as $item) {
        $productId = $item['id'] ?? '';
        $qty = max(1, (int)($item['qty'] ?? 1));

        if (!isset($dbProducts[$productId])) {
            throw new Exception("Produto '{$productId}' não encontrado no catálogo.");
        }

        $prod = $dbProducts[$productId];
        $realUnitPrice = (float)$prod['price'];
        $itemTotal = $realUnitPrice * $qty;
        $subtotal += $itemTotal;

        $recalculatedItems[] = [
            'id' => $prod['id'],
            'name' => $prod['name'],
            'tagline' => $prod['tagline'],
            'price' => $realUnitPrice,
            'qty' => $qty,
            'subtotal' => $itemTotal,
            'image' => $prod['image']
        ];

        $mpItems[] = [
            'id' => $prod['id'],
            'title' => 'useLOVELY - ' . $prod['name'] . ' (' . $prod['tagline'] . ')',
            'quantity' => $qty,
            'currency_id' => 'BRL',
            'unit_price' => $realUnitPrice
        ];
    }

    $shippingAmount = 0.00; // Frete Grátis na oferta
    $discountAmount = 0.00;
    $totalAmount = $subtotal + $shippingAmount - $discountAmount;

    // 2. GERAR IDENTIFICADOR ÚNICO DO PEDIDO
    $orderNumber = 'PEDIDO-' . rand(1000, 9999);
    $orderId = 'LV-' . uniqid();

    // 3. SALVAR PEDIDO NO MYSQL COM STATUS PENDING
    $sql = "INSERT INTO orders (
        id, order_number, customer_name, customer_email, customer_phone, customer_cpf,
        shipping_address, items, subtotal, shipping_amount, discount_amount, total_amount,
        payment_method, payment_status, order_status, external_reference, stock_reduced
    ) VALUES (
        :id, :order_number, :customer_name, :customer_email, :customer_phone, :customer_cpf,
        :shipping_address, :items, :subtotal, :shipping_amount, :discount_amount, :total_amount,
        'Mercado Pago', 'pending', 'awaiting_payment', :external_reference, 0
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $orderId,
        ':order_number' => $orderNumber,
        ':customer_name' => $customer['name'] ?? 'Cliente',
        ':customer_email' => $customer['email'] ?? '',
        ':customer_phone' => $customer['phone'] ?? '',
        ':customer_cpf' => $customer['cpf'] ?? '',
        ':shipping_address' => json_encode($address, JSON_UNESCAPED_UNICODE),
        ':items' => json_encode($recalculatedItems, JSON_UNESCAPED_UNICODE),
        ':subtotal' => $subtotal,
        ':shipping_amount' => $shippingAmount,
        ':discount_amount' => $discountAmount,
        ':total_amount' => $totalAmount,
        ':external_reference' => $orderNumber
    ]);

    // 4. CRIAR PREFERÊNCIA NO MERCADO PAGO
    $siteUrl = rtrim(get_env('SITE_URL', 'http://localhost:3000'), '/');
    $environment = get_env('ENVIRONMENT', 'sandbox');

    $mpService = new MercadoPagoService();

    $preferencePayload = [
        'items' => $mpItems,
        'payer' => [
            'name' => $customer['name'] ?? 'Cliente',
            'email' => $customer['email'] ?? '',
            'phone' => [
                'number' => preg_replace('/\D/', '', $customer['phone'] ?? '')
            ],
            'identification' => [
                'type' => 'CPF',
                'number' => preg_replace('/\D/', '', $customer['cpf'] ?? '')
            ],
            'address' => [
                'zip_code' => $address['cep'] ?? '',
                'street_name' => $address['street'] ?? '',
                'street_number' => (int)($address['number'] ?? 0)
            ]
        ],
        'back_urls' => [
            'success' => $siteUrl . '/pagamento/sucesso.php?external_reference=' . $orderNumber,
            'pending' => $siteUrl . '/pagamento/pendente.php?external_reference=' . $orderNumber,
            'failure' => $siteUrl . '/pagamento/erro.php?external_reference=' . $orderNumber
        ],
        'auto_return' => 'approved',
        'external_reference' => $orderNumber,
        'notification_url' => $siteUrl . '/api/mercadopago/webhook.php'
    ];

    $preference = $mpService->createPreference($preferencePayload);

    $preferenceId = $preference['id'] ?? '';
    $initPoint = $environment === 'sandbox' && isset($preference['sandbox_init_point'])
        ? $preference['sandbox_init_point']
        : ($preference['init_point'] ?? '');

    // 5. ATUALIZAR ID DA PREFERÊNCIA NO PEDIDO
    $stmt = $pdo->prepare("UPDATE orders SET mercado_pago_preference_id = :pref_id WHERE id = :id");
    $stmt->execute([
        ':pref_id' => $preferenceId,
        ':id' => $orderId
    ]);

    echo json_encode([
        'status' => 'success',
        'orderNumber' => $orderNumber,
        'orderId' => $orderId,
        'preferenceId' => $preferenceId,
        'initPoint' => $initPoint,
        'totalAmount' => $totalAmount
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
