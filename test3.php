<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
$code = file_get_contents('api/forgot_password.php');
// Replace catch (Exception $e) with catch (Throwable $e) to catch fatal errors too.
$code = str_replace('catch (Exception $e)', 'catch (Throwable $e)', $code);
// Hardcode the email input
$code = str_replace("\$input = json_decode(file_get_contents('php://input'), true);", "\$input = ['email' => 'admin@uselovely.com.br'];", $code);
file_put_contents('api/test_forgot.php', $code);
