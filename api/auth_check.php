<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if (isset($_SESSION['user'])) {
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
