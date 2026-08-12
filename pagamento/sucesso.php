<?php
require_once __DIR__ . '/../config/database.php';

$orderRef = $_GET['external_reference'] ?? '';
$order = null;

if (!empty($orderRef)) {
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = :ref OR external_reference = :ref OR id = :ref");
        $stmt->execute([':ref' => $orderRef]);
        $order = $stmt->fetch();
    } catch (Exception $e) {
        // Ignora erro e exibe layout amigável
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Recebido | use LOVELY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#FAF8F5] text-neutral-800 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="max-w-xl w-full bg-white rounded-3xl p-6 sm:p-10 shadow-2xl border border-rose-100 text-center space-y-6">
        
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-md">
            <i data-lucide="check-circle-2" class="w-10 h-10"></i>
        </div>

        <div class="space-y-2">
            <span class="text-xs uppercase font-bold tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Pedido Recebido</span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-neutral-900">Obrigado pela sua compra!</h1>
            <p class="text-xs sm:text-sm text-neutral-600 font-light max-w-md mx-auto">
                Assim que o pagamento for confirmado pelo Mercado Pago, iniciaremos imediatamente a preparação do seu pedido.
            </p>
        </div>

        <?php if ($order): ?>
            <?php 
                $address = json_decode($order['shipping_address'], true) ?? [];
                $items = json_decode($order['items'], true) ?? [];
            ?>
            <div class="bg-neutral-50 rounded-2xl p-5 text-left text-xs space-y-4 border border-neutral-200">
                <div class="flex justify-between items-center border-b border-neutral-200 pb-3">
                    <div>
                        <span class="text-neutral-400 block text-[10px] uppercase font-bold">Número do Pedido</span>
                        <span class="font-serif text-xl font-bold text-neutral-900"><?= htmlspecialchars($order['order_number']) ?></span>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                        <?= htmlspecialchars($order['payment_status'] === 'paid' ? 'Pagamento Aprovado ✓' : 'Aguardando Confirmação') ?>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="font-bold text-neutral-900 block mb-1">Cliente:</span>
                        <p class="text-neutral-600"><?= htmlspecialchars($order['customer_name']) ?></p>
                        <p class="text-neutral-500"><?= htmlspecialchars($order['customer_email']) ?></p>
                    </div>
                    <div>
                        <span class="font-bold text-neutral-900 block mb-1">Endereço de Entrega:</span>
                        <p class="text-neutral-600"><?= htmlspecialchars($address['street'] ?? '') ?>, <?= htmlspecialchars($address['number'] ?? '') ?></p>
                        <p class="text-neutral-500"><?= htmlspecialchars($address['neighborhood'] ?? '') ?> - <?= htmlspecialchars($address['city'] ?? '') ?>/<?= htmlspecialchars($address['state'] ?? '') ?></p>
                    </div>
                </div>

                <div class="border-t border-neutral-200 pt-3">
                    <span class="font-bold text-neutral-900 block mb-2">Itens Comprados:</span>
                    <div class="space-y-2">
                        <?php foreach ($items as $item): ?>
                            <div class="flex justify-between items-center text-neutral-700">
                                <span><?= htmlspecialchars($item['qty']) ?>x <?= htmlspecialchars($item['name']) ?></span>
                                <span class="font-semibold">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="border-t border-neutral-200 pt-3 flex justify-between items-center font-bold text-neutral-900 text-sm">
                    <span>Total Pago:</span>
                    <span class="text-rose-600 text-lg">R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></span>
                </div>
            </div>
        <?php else: ?>
            <div class="p-4 rounded-xl bg-neutral-100 text-xs text-neutral-500">
                Identificador do pedido: <strong><?= htmlspecialchars($orderRef ?: 'Em processamento') ?></strong>
            </div>
        <?php endif; ?>

        <div class="pt-2">
            <a href="../index.php" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition-all hover:scale-105">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                <span>Continuar Comprando</span>
            </a>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
