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
$email = trim($input['email'] ?? '');

if (empty($email)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'O campo e-mail é obrigatório.']);
    exit;
}

try {
    $pdo = get_db_connection();

    $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        // Para evitar enumeração de e-mails, retornamos sucesso mesmo se não encontrar,
        // mas sem enviar nada.
        echo json_encode([
            'status' => 'success',
            'message' => 'Se o e-mail estiver cadastrado, você receberá um link de recuperação.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE id = :id");
    $stmt->execute([
        ':token' => $token,
        ':expires' => $expires,
        ':id' => $user['id']
    ]);

    require_once __DIR__ . '/../services/Mailer.php';
    
    // Enviar E-mail usando PHPMailer
    $resetLink = get_env('SITE_URL') . "/?reset_token=" . $token;
    
    $emailSent = Mailer::sendResetPasswordEmail($email, $user['name'], $resetLink);

    if ($emailSent) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Se o e-mail estiver cadastrado, você receberá um link de recuperação.'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Erro interno ao tentar enviar o e-mail de recuperação.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
