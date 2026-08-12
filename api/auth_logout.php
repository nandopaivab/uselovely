<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

unset($_SESSION['user']);
session_destroy();

echo json_encode([
    'status' => 'success',
    'message' => 'Sessão encerrada com sucesso.'
]);
