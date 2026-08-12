<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = get_db_connection();
    $stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
    $rows = $stmt->fetchAll();

    $products = [];
    foreach ($rows as $r) {
        $products[] = [
            'id' => $r['id'],
            'name' => $r['name'],
            'category' => $r['category'],
            'genderGroup' => $r['gender_group'],
            'genderTag' => $r['gender_tag'],
            'genderBadge' => $r['gender_badge'],
            'tagline' => $r['tagline'],
            'description' => $r['description'],
            'price' => (float)$r['price'],
            'volume' => $r['volume'],
            'image' => $r['image'],
            'colorTheme' => $r['color_theme'],
            'bgGradient' => $r['bg_gradient'],
            'btnBg' => $r['btn_bg'],
            'shadowClass' => $r['shadow_class'],
            'accentColor' => $r['accent_color'],
            'accentText' => $r['accent_text'],
            'notes' => [
                'top' => $r['notes_top'],
                'heart' => $r['notes_heart'],
                'base' => $r['notes_base']
            ],
            'sensation' => $r['sensation']
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $products
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
