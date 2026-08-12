<?php
require_once __DIR__ . '/../config/database.php';

$orderRef = $_GET['external_reference'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Não Aprovado | use LOVELY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#FAF8F5] text-neutral-800 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-rose-200 text-center space-y-6">
        
        <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto shadow-md">
            <i data-lucide="alert-circle" class="w-10 h-10"></i>
        </div>

        <div class="space-y-2">
            <span class="text-xs uppercase font-bold tracking-widest text-rose-600 bg-rose-50 px-3 py-1 rounded-full">Pagamento Não Aprovado</span>
            <h1 class="font-serif text-3xl font-bold text-neutral-900">Não conseguimos processar o seu pagamento.</h1>
            <p class="text-xs text-neutral-600 font-light leading-relaxed">
                Por favor, tente novamente utilizando outro meio de pagamento ou verifique os dados informados no cartão.
            </p>
        </div>

        <?php if (!empty($orderRef)): ?>
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-100 text-xs text-rose-700 font-semibold">
                Referência do Pedido: <?= htmlspecialchars($orderRef) ?>
            </div>
        <?php endif; ?>

        <div class="pt-2">
            <a href="../index.php" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition-all hover:scale-105">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>Tentar Novamente</span>
            </a>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
