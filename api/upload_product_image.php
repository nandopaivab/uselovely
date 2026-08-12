<?php
// api/upload_product_image.php - Upload de imagens de produtos para o servidor e MySQL
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/env.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Não autorizado. Faça login no painel ADM.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido.']);
    exit;
}

$productId = isset($_POST['product_id']) ? trim($_POST['product_id']) : '';
if (empty($productId)) {
    echo json_encode(['status' => 'error', 'message' => 'ID do produto não informado.']);
    exit;
}

if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum arquivo enviado ou ocorreu um erro no upload.']);
    exit;
}

$file = $_FILES['product_image'];
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];

if (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
    echo json_encode(['status' => 'error', 'message' => 'Tipo de arquivo inválido. Envie JPG, PNG, WEBP ou GIF.']);
    exit;
}

// Limite de 5MB
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['status' => 'error', 'message' => 'Arquivo muito grande. O limite máximo é de 5MB.']);
    exit;
}

$uploadDir = __DIR__ . '/../assets/images/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$cleanName = preg_replace('/[^a-z0-9_-]/i', '_', strtolower($productId));
$newFilename = $cleanName . '_' . time() . '.' . strtolower($extension);
$targetPath = $uploadDir . $newFilename;
$relativePath = 'assets/images/' . $newFilename;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("UPDATE products SET image = :image, updated_at = NOW() WHERE id = :id");
        $stmt->execute([
            ':image' => $relativePath,
            ':id' => $productId
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Imagem enviada e atualizada com sucesso!',
            'imagePath' => $relativePath
        ]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Falha ao salvar a imagem no servidor. Verifique permissões da pasta.']);
}
