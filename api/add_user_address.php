<?php
// api/add_user_address.php - Cadastra um novo endereço de entrega para o cliente
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Você precisa estar logado para cadastrar endereços.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$userId = $_SESSION['user']['id'];
$recipientName = trim($input['recipient_name'] ?? $_SESSION['user']['name']);
$cep = trim($input['cep'] ?? '');
$street = trim($input['street'] ?? '');
$number = trim($input['number'] ?? '');
$complement = trim($input['complement'] ?? '');
$neighborhood = trim($input['neighborhood'] ?? '');
$city = trim($input['city'] ?? '');
$state = strtoupper(trim($input['state'] ?? ''));
$isDefault = !empty($input['is_default']) ? 1 : 0;

if (empty($cep) || empty($street) || empty($number) || empty($neighborhood) || empty($city) || empty($state)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Preencha CEP, Rua, Número, Bairro, Cidade e Estado.']);
    exit;
}

try {
    $pdo = get_db_connection();

    // Se for o primeiro endereço ou marcado como padrão, desmarca outros padrões
    $chk = $pdo->prepare("SELECT COUNT(*) FROM user_addresses WHERE user_id = :user_id");
    $chk->execute([':user_id' => $userId]);
    $count = $chk->fetchColumn();

    if ($count == 0 || $isDefault) {
        $isDefault = 1;
        $unmark = $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = :user_id");
        $unmark->execute([':user_id' => $userId]);
    }

    $stmt = $pdo->prepare("INSERT INTO user_addresses (
        user_id, recipient_name, cep, street, number, complement, neighborhood, city, state, is_default
    ) VALUES (
        :user_id, :recipient_name, :cep, :street, :number, :complement, :neighborhood, :city, :state, :is_default
    )");

    $stmt->execute([
        ':user_id' => $userId,
        ':recipient_name' => $recipientName,
        ':cep' => $cep,
        ':street' => $street,
        ':number' => $number,
        ':complement' => $complement,
        ':neighborhood' => $neighborhood,
        ':city' => $city,
        ':state' => $state,
        ':is_default' => $isDefault
    ]);

    $addressId = $pdo->lastInsertId();

    echo json_encode([
        'status' => 'success',
        'message' => 'Novo endereço cadastrado com sucesso!',
        'addressId' => $addressId
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
