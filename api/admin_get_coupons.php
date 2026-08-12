<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = get_db_connection();
    
    // Fetch coupons and optionally left join with users to get the email if user_id is set
    $stmt = $pdo->query("
        SELECT c.*, u.email as user_email 
        FROM coupons c 
        LEFT JOIN users u ON c.user_id = u.id 
        ORDER BY c.created_at DESC
    ");
    $coupons = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'data' => $coupons
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
