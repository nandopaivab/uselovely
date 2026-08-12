<?php
ini_set('display_errors', '0');
error_reporting(E_ALL);
ob_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    ob_end_clean();
    exit(0);
}

require_once __DIR__ . '/../config/database.php';

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = trim($input['email'] ?? '');

    if (empty($email)) {
        http_response_code(400);
        ob_end_clean();
        echo json_encode(['status' => 'error', 'message' => 'O campo e-mail é obrigatório.']);
        exit;
    }

    $pdo = get_db_connection();

    $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        // Para evitar enumeração de e-mails, retornamos sucesso mesmo se não encontrar,
        // mas sem enviar nada.
        ob_end_clean();
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

    ob_end_clean();
    if ($emailSent) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Se o e-mail estiver cadastrado, você receberá um link de recuperação.'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Erro interno ao tentar enviar o e-mail de recuperação.']);
    }

} catch (\Throwable $e) {
    ob_end_clean();
    error_log("Erro no forgot_password: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erro interno do servidor. Tente novamente mais tarde.']);
}
