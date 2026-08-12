<?php
// api/add_product.php - Endpoint para adicionar um novo produto ao MySQL
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['name'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'O nome do produto é obrigatório.']);
    exit;
}

try {
    $pdo = get_db_connection();

    $name = trim($input['name']);
    
    // Gerar ID amigável ou único
    if (!empty($input['id'])) {
        $id = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($input['id'])));
    } else {
        $id = preg_replace('/[^a-z0-9]/', '-', strtolower(trim($name))) . '-' . rand(100, 999);
    }

    // Verificar se ID já existe
    $chk = $pdo->prepare("SELECT COUNT(*) FROM products WHERE id = :id");
    $chk->execute([':id' => $id]);
    if ($chk->fetchColumn() > 0) {
        $id = $id . '-' . rand(1000, 9999);
    }

    $tagline = $input['tagline'] ?? 'Aroma Exclusivo';
    $description = $input['description'] ?? 'Fragrância marcante e envolvente para o dia a dia.';
    $price = (float)($input['price'] ?? 49.90);
    $stockQuantity = isset($input['stockQuantity']) ? (int)$input['stockQuantity'] : (isset($input['stock_quantity']) ? (int)$input['stock_quantity'] : 100);
    $genderTag = $input['genderTag'] ?? 'Feminino';
    $image = !empty($input['image']) ? $input['image'] : 'assets/images/velvet_bloom.jpg';
    $olfactoryReference = $input['olfactoryReference'] ?? ($input['olfactory_reference'] ?? 'Importada');
    
    $notesTop = isset($input['notes']['top']) ? $input['notes']['top'] : ($input['notes_top'] ?? 'Notas de Saída Frescas');
    $notesHeart = isset($input['notes']['heart']) ? $input['notes']['heart'] : ($input['notes_heart'] ?? 'Acordes Florais');
    $notesBase = isset($input['notes']['base']) ? $input['notes']['base'] : ($input['notes_base'] ?? 'Fundo Ambarado & Baunilha');
    $sensation = $input['sensation'] ?? 'Sensação agradável e aveludada na pele.';

    // Determinar grupo e badge de gênero
    if (strpos($genderTag, 'Feminino') !== false) {
        $genderGroup = 'feminino';
        $genderBadge = (strpos($genderTag, 'Envolvente') !== false) ? 'bg-purple-100 text-purple-700 font-semibold' : 'bg-pink-100 text-pink-700 font-semibold';
    } else {
        $genderGroup = 'masculino-unisex';
        $genderBadge = (strpos($genderTag, 'Compartilhável') !== false) ? 'bg-amber-100 text-amber-800 font-semibold' : ((strpos($genderTag, 'Noturno') !== false) ? 'bg-slate-200 text-slate-800 font-semibold' : 'bg-teal-100 text-teal-800 font-semibold');
    }

    $category = $input['category'] ?? 'floral';
    $colorTheme = $input['color_theme'] ?? 'rose';
    $bgGradient = $input['bg_gradient'] ?? 'from-pink-100 via-rose-50 to-pink-200';
    $btnBg = $input['btn_bg'] ?? 'bg-rose-500 hover:bg-rose-600 text-white';
    $shadowClass = $input['shadow_class'] ?? 'shadow-glow-rose';
    $accentColor = $input['accent_color'] ?? '#E8A5B8';
    $accentText = $input['accent_text'] ?? 'text-rose-600';
    $volume = $input['volume'] ?? '236 mL / 8 fl oz';

    $sql = "INSERT INTO products (
        id, name, category, gender_group, gender_tag, gender_badge, tagline, description, price, volume, image,
        color_theme, bg_gradient, btn_bg, shadow_class, accent_color, accent_text, notes_top, notes_heart, notes_base, sensation, olfactory_reference, stock_quantity
    ) VALUES (
        :id, :name, :category, :gender_group, :gender_tag, :gender_badge, :tagline, :description, :price, :volume, :image,
        :color_theme, :bg_gradient, :btn_bg, :shadow_class, :accent_color, :accent_text, :notes_top, :notes_heart, :notes_base, :sensation, :olfactory_reference, :stock_quantity
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':category' => $category,
        ':gender_group' => $genderGroup,
        ':gender_tag' => $genderTag,
        ':gender_badge' => $genderBadge,
        ':tagline' => $tagline,
        ':description' => $description,
        ':price' => $price,
        ':volume' => $volume,
        ':image' => $image,
        ':color_theme' => $colorTheme,
        ':bg_gradient' => $bgGradient,
        ':btn_bg' => $btnBg,
        ':shadow_class' => $shadowClass,
        ':accent_color' => $accentColor,
        ':accent_text' => $accentText,
        ':notes_top' => $notesTop,
        ':notes_heart' => $notesHeart,
        ':notes_base' => $notesBase,
        ':sensation' => $sensation,
        ':olfactory_reference' => $olfactoryReference,
        ':stock_quantity' => $stockQuantity
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Novo produto cadastrado com sucesso!',
        'productId' => $id
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
