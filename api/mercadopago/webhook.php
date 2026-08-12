<?php
/**
 * Webhook Oficial do Mercado Pago para Confirmação Segura e Idempotente de Pagamentos
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../../services/mercadopago.php';

function log_webhook($msg) {
    $logDir = __DIR__ . '/../../logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $logFile = $logDir . '/mercadopago.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[{$timestamp}] {$msg}\n", FILE_APPEND);
}

try {
    // Capturar payload ou query parameters do Mercado Pago
    $rawBody = file_get_contents('php://input');
    $input = json_decode($rawBody, true) ?? [];
    
    $type = $input['type'] ?? ($_GET['type'] ?? ($_GET['topic'] ?? ''));
    $paymentId = $input['data']['id'] ?? ($input['id'] ?? ($_GET['id'] ?? ($_GET['data_id'] ?? '')));

    log_webhook("Webhook recebido. Type: '{$type}', Payment ID: '{$paymentId}'");

    if (empty($paymentId)) {
        // Se for um evento que não possui ID de pagamento, apenas confirma recebimento 200 OK
        echo json_encode(['status' => 'ignored', 'reason' => 'Sem ID de pagamento.']);
        exit;
    }

    // 1. CONSULTAR A API OFICIAL DO MERCADO PAGO UTILIZANDO O BACKEND
    // NUNCA CONFIAR APENAS NOS PARÂMETROS RECEBIDOS NO BODY
    $mpService = new MercadoPagoService();
    $paymentInfo = $mpService->getPaymentInfo($paymentId);

    $paymentStatus = $paymentInfo['status'] ?? 'unknown';
    $externalReference = $paymentInfo['external_reference'] ?? '';
    $transactionAmount = (float)($paymentInfo['transaction_amount'] ?? 0.0);
    $currencyId = $paymentInfo['currency_id'] ?? 'BRL';

    log_webhook("Consulta MP realizada. Status MP: '{$paymentStatus}', Ref: '{$externalReference}', Valor: R$ {$transactionAmount}");

    if (empty($externalReference)) {
        log_webhook("ERRO: external_reference ausente no pagamento {$paymentId}.");
        echo json_encode(['status' => 'error', 'message' => 'external_reference ausente.']);
        exit;
    }

    $pdo = get_db_connection();

    // 2. LOCALIZAR O PEDIDO NO BANCO DE DADOS
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = :ref OR external_reference = :ref");
    $stmt->execute([':ref' => $externalReference]);
    $order = $stmt->fetch();

    if (!$order) {
        log_webhook("ERRO: Pedido '{$externalReference}' não encontrado no MySQL.");
        echo json_encode(['status' => 'error', 'message' => 'Pedido não encontrado.']);
        exit;
    }

    // 3. IDEMPOTÊNCIA & ATUALIZAÇÃO DE STATUS
    $currentPaymentStatus = $order['payment_status'];
    $isAlreadyPaid = ($currentPaymentStatus === 'paid');
    $stockReduced = (int)($order['stock_reduced'] ?? 0);

    // Mapear status do Mercado Pago para status internos
    $mappedPaymentStatus = 'pending';
    $mappedOrderStatus = 'awaiting_payment';

    switch ($paymentStatus) {
        case 'approved':
            $mappedPaymentStatus = 'paid';
            $mappedOrderStatus = 'paid';
            break;
        case 'in_process':
        case 'pending':
            $mappedPaymentStatus = 'pending';
            $mappedOrderStatus = 'awaiting_payment';
            break;
        case 'rejected':
            $mappedPaymentStatus = 'rejected';
            $mappedOrderStatus = 'cancelled';
            break;
        case 'cancelled':
            $mappedPaymentStatus = 'cancelled';
            $mappedOrderStatus = 'cancelled';
            break;
        case 'refunded':
            $mappedPaymentStatus = 'refunded';
            $mappedOrderStatus = 'cancelled';
            break;
        case 'charged_back':
            $mappedPaymentStatus = 'charged_back';
            $mappedOrderStatus = 'cancelled';
            break;
    }

    // 4. SE PAGAMENTO APROVADO E ESTOQUE AINDA NÃO BAIXADO -> BAIXAR ESTOQUE 1 ÚNICA VEZ (IDEMPOTÊNCIA)
    if ($mappedPaymentStatus === 'paid' && $stockReduced === 0) {
        log_webhook("Pagamento APROVADO para pedido {$externalReference}. Processando baixa de estoque idempotente...");

        $items = json_decode($order['items'], true) ?? [];
        foreach ($items as $item) {
            $pId = $item['id'] ?? '';
            $qty = (int)($item['qty'] ?? 1);
            if (!empty($pId)) {
                $updateStockStmt = $pdo->prepare("UPDATE products SET stock_quantity = GREATEST(0, stock_quantity - :qty) WHERE id = :id");
                $updateStockStmt->execute([':qty' => $qty, ':id' => $pId]);
            }
        }

        $stockReduced = 1;
    }

    // 5. ATUALIZAR PEDIDO NO BANCO MYSQL
    $updateSql = "UPDATE orders SET
        payment_status = :p_status,
        order_status = :o_status,
        mercado_pago_payment_id = :p_id,
        stock_reduced = :stock_red,
        updated_at = CURRENT_TIMESTAMP
        WHERE id = :id";

    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([
        ':p_status' => $mappedPaymentStatus,
        ':o_status' => $mappedOrderStatus,
        ':p_id' => $paymentId,
        ':stock_red' => $stockReduced,
        ':id' => $order['id']
    ]);

    log_webhook("Pedido {$externalReference} atualizado no MySQL com sucesso. PaymentStatus: '{$mappedPaymentStatus}', OrderStatus: '{$mappedOrderStatus}', StockReduced: {$stockReduced}");

    echo json_encode([
        'status' => 'success',
        'orderNumber' => $externalReference,
        'paymentStatus' => $mappedPaymentStatus,
        'orderStatus' => $mappedOrderStatus
    ]);

} catch (Exception $e) {
    log_webhook("EXCEÇÃO no Webhook: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
