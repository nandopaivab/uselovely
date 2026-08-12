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

if (!$input || empty($input['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID do produto é obrigatório.']);
    exit;
}

try {
    $pdo = get_db_connection();

    $id = $input['id'];
    $name = $input['name'] ?? '';
    $tagline = $input['tagline'] ?? '';
    $description = $input['description'] ?? '';
    $price = (float)($input['price'] ?? 49.90);
    $genderTag = $input['genderTag'] ?? 'Feminino';
    $image = $input['image'] ?? '';
    $notesTop = $input['notes']['top'] ?? '';
    $notesHeart = $input['notes']['heart'] ?? '';
    $notesBase = $input['notes']['base'] ?? '';

    // Determine gender group and badge
    if (strpos($genderTag, 'Feminino') !== false) {
        $genderGroup = 'feminino';
        $genderBadge = (strpos($genderTag, 'Envolvente') !== false) ? 'bg-purple-100 text-purple-700 font-semibold' : 'bg-pink-100 text-pink-700 font-semibold';
    } else {
        $genderGroup = 'masculino-unisex';
        $genderBadge = (strpos($genderTag, 'Compartilhável') !== false) ? 'bg-amber-100 text-amber-800 font-semibold' : ((strpos($genderTag, 'Noturno') !== false) ? 'bg-slate-200 text-slate-800 font-semibold' : 'bg-teal-100 text-teal-800 font-semibold');
    }

    $sql = "UPDATE products SET
        name = :name,
        tagline = :tagline,
        description = :description,
        price = :price,
        gender_group = :gender_group,
        gender_tag = :gender_tag,
        gender_badge = :gender_badge,
        image = :image,
        notes_top = :notes_top,
        notes_heart = :notes_heart,
        notes_base = :notes_base
    WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':tagline' => $tagline,
        ':description' => $description,
        ':price' => $price,
        ':gender_group' => $genderGroup,
        ':gender_tag' => $genderTag,
        ':gender_badge' => $genderBadge,
        ':image' => $image,
        ':notes_top' => $notesTop,
        ':notes_heart' => $notesHeart,
        ':notes_base' => $notesBase,
        ':id' => $id
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Produto atualizado com sucesso no banco de dados local.'
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
