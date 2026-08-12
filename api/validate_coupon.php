<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$code = strtoupper(trim($input['code'] ?? ''));

if (empty($code)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Código do cupom é obrigatório.']);
    exit;
}

// Verifica Datas Duplas e Black Friday
$today = date('d/m');
$isBlackFriday = false; // Em um cenário real, você verificaria se é a 4ª sexta de novembro

// Lógica de Black Friday (ex: Novembro)
if (date('m') === '11' && date('d') >= '20' && date('d') <= '30') {
    // Para simplificar, aceitamos na semana da Black Friday
    $isBlackFriday = true;
}

if ($code === 'BLACK20' && $isBlackFriday) {
    echo json_encode([
        'status' => 'success',
        'coupon' => [
            'code' => 'BLACK20',
            'type' => 'percentage',
            'value' => 20
        ]
    ]);
    exit;
}

// Datas Duplas (1/1, 2/2, 3/3, etc)
$day = date('d');
$month = date('m');
if ($code === 'LOVELY15' && $day === $month) {
    echo json_encode([
        'status' => 'success',
        'coupon' => [
            'code' => 'LOVELY15',
            'type' => 'percentage',
            'value' => 15
        ]
    ]);
    exit;
}

// Verifica cupons no banco de dados
try {
    $pdo = get_db_connection();
    
    // Verifica primeiro se o cliente mandou o código base de boas vindas ou cashback
    // Vamos buscar os cupons disponíveis para o usuário caso ele digite BEMVINDO ou CASHBACK genericamente
    $userId = $_SESSION['user']['id'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = :code");
    $stmt->execute([':code' => $code]);
    $coupon = $stmt->fetch();

    // Se não encontrou o código exato, mas o usuário digitou BEMVINDO ou CASHBACK e está logado:
    if (!$coupon && $userId && ($code === 'BEMVINDO12' || $code === 'CASHBACK10' || $code === 'BEMVINDO' || $code === 'CASHBACK')) {
        $prefix = str_replace(['12', '10'], '', $code) . '%';
        $stmt = $pdo->prepare("SELECT * FROM coupons WHERE user_id = :user_id AND used_count < usage_limit AND code LIKE :prefix ORDER BY id ASC LIMIT 1");
        $stmt->execute([':user_id' => $userId, ':prefix' => $prefix]);
        $coupon = $stmt->fetch();
    }

    if (!$coupon) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Cupom inválido ou não encontrado.']);
        exit;
    }

    if ($coupon['used_count'] >= $coupon['usage_limit']) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Este cupom já foi utilizado.']);
        exit;
    }

    if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) < time()) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Este cupom está expirado.']);
        exit;
    }

    if (!empty($coupon['user_id']) && $coupon['user_id'] != $userId) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Este cupom pertence a outro usuário.']);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'coupon' => [
            'code' => $coupon['code'],
            'type' => $coupon['type'],
            'value' => (float)$coupon['value']
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
