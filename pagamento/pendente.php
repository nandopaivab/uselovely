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
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Pendente | use LOVELY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#FAF8F5] text-neutral-800 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-amber-100 text-center space-y-6">
        
        <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto shadow-md">
            <i data-lucide="clock" class="w-10 h-10"></i>
        </div>

        <div class="space-y-2">
            <span class="text-xs uppercase font-bold tracking-widest text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Aguardando Pagamento</span>
            <h1 class="font-serif text-3xl font-bold text-neutral-900">Estamos aguardando a confirmação do seu pagamento.</h1>
            <p class="text-xs text-neutral-600 font-light leading-relaxed">
                Se você pagou via PIX ou Boleto, assim que a instituição financeira confirmar a transação, o status do seu pedido será atualizado automaticamente!
            </p>
        </div>

        <?php if ($order): ?>
            <div class="bg-neutral-50 rounded-2xl p-4 text-xs space-y-2 border border-neutral-200 text-left">
                <div class="flex justify-between">
                    <span class="text-neutral-500">Número do Pedido:</span>
                    <span class="font-bold text-neutral-900"><?= htmlspecialchars($order['order_number']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-neutral-500">Valor Total:</span>
                    <span class="font-bold text-rose-600">R$ <?= number_format($order['total_amount'], 2, ',', '.') ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="pt-2">
            <a href="../index.php" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Voltar para a Loja</span>
            </a>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
