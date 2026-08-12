<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
    require_once __DIR__ . '/../config/database.php';
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT id, name, email, phone, cpf, role FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user']['id']]);
        $freshUser = $stmt->fetch();
        if ($freshUser) {
            $_SESSION['user'] = $freshUser;
        }
    } catch (Exception $e) {}

    echo json_encode([
        'status' => 'success',
        'loggedIn' => true,
        'user' => $_SESSION['user']
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        'status' => 'success',
        'loggedIn' => false,
        'user' => null
    ]);
}
