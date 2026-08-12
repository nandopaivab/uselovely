<?php
// api/get_crm_stats.php - API de Relatórios Financeiros, Vendas e CRM de Clientes
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Não autorizado. Faça login no painel ADM.']);
    exit;
}

try {
    $pdo = getDbConnection();

    // 1. Faturamento Total e Métricas de Pedidos
    $stmtStats = $pdo->query("
        SELECT 
            COUNT(*) as totalOrders,
            SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paidOrders,
            SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as totalRevenue,
            AVG(CASE WHEN payment_status = 'paid' THEN total_amount ELSE NULL END) as avgTicket
        FROM orders
    ");
    $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

    $totalRevenue = (float)($stats['totalRevenue'] ?? 0);
    $totalOrders = (int)($stats['totalOrders'] ?? 0);
    $paidOrders = (int)($stats['paidOrders'] ?? 0);
    $avgTicket = (float)($stats['avgTicket'] ?? 0);

    // 2. CRM de Clientes (Histórico e Gasto Total por Cliente)
    $stmtCustomers = $pdo->query("
        SELECT 
            customer_email as email,
            customer_name as name,
            customer_phone as phone,
            customer_cpf as cpf,
            COUNT(*) as totalOrders,
            SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as totalSpent,
            MAX(created_at) as lastPurchaseDate
        FROM orders
        GROUP BY customer_email
        ORDER BY totalSpent DESC, totalOrders DESC
    ");
    $customers = $stmtCustomers->fetchAll(PDO::FETCH_ASSOC);

    // Complementar com usuários cadastrados na tabela users que ainda não compraram
    $stmtRegisteredUsers = $pdo->query("SELECT name, email, phone, created_at FROM users");
    $registeredUsers = $stmtRegisteredUsers->fetchAll(PDO::FETCH_ASSOC);

    $customerMap = [];
    foreach ($customers as $c) {
        $customerMap[strtolower($c['email'])] = [
            'name' => $c['name'],
            'email' => $c['email'],
            'phone' => $c['phone'],
            'cpf' => $c['cpf'],
            'totalOrders' => (int)$c['totalOrders'],
            'totalSpent' => (float)$c['totalSpent'],
            'lastPurchaseDate' => $c['lastPurchaseDate']
        ];
    }

    foreach ($registeredUsers as $ru) {
        $emailKey = strtolower($ru['email']);
        if (!isset($customerMap[$emailKey])) {
            $customerMap[$emailKey] = [
                'name' => $ru['name'],
                'email' => $ru['email'],
                'phone' => $ru['phone'],
                'cpf' => '',
                'totalOrders' => 0,
                'totalSpent' => 0.0,
                'lastPurchaseDate' => $ru['created_at']
            ];
        }
    }

    $crmList = array_values($customerMap);

    // 3. Resumo de Estoque
    $stmtStock = $pdo->query("SELECT id, name, stock_quantity, price FROM products ORDER BY stock_quantity ASC");
    $productsStock = $stmtStock->fetchAll(PDO::FETCH_ASSOC);

    $lowStockItems = array_filter($productsStock, function($p) {
        return (int)$p['stock_quantity'] < 10;
    });

    echo json_encode([
        'status' => 'success',
        'metrics' => [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'paidOrders' => $paidOrders,
            'avgTicket' => $avgTicket,
            'lowStockCount' => count($lowStockItems)
        ],
        'customers' => $crmList,
        'stockSummary' => array_map(function($p) {
            return [
                'id' => $p['id'],
                'name' => $p['name'],
                'stock' => (int)$p['stock_quantity'],
                'price' => (float)$p['price'],
                'isLowStock' => ((int)$p['stock_quantity'] < 10)
            ];
        }, $productsStock)
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
