<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/env.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    public static function sendResetPasswordEmail($toEmail, $toName, $resetLink) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = get_env('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = get_env('SMTP_USER');
            $mail->Password   = get_env('SMTP_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Porta 465 geralmente usa SSL/TLS implícito
            $mail->Port       = get_env('SMTP_PORT');
            $mail->CharSet    = 'UTF-8';

            // Remetente e Destinatário
            $mail->setFrom(get_env('SMTP_USER'), 'Lovely');
            $mail->addAddress($toEmail, $toName);

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - Lovely';
            $mail->Body    = "
                <h3>Olá, {$toName}!</h3>
                <p>Recebemos uma solicitação para redefinir a senha da sua conta.</p>
                <p>Para criar uma nova senha, clique no link abaixo (válido por 1 hora):</p>
                <p><a href='{$resetLink}' style='display:inline-block; padding:10px 20px; background-color:#f43f5e; color:#ffffff; text-decoration:none; border-radius:5px;'>Redefinir Minha Senha</a></p>
                <br>
                <p>Se você não solicitou essa alteração, pode ignorar este e-mail.</p>
                <p>Atenciosamente,<br>Equipe Lovely</p>
            ";
            $mail->AltBody = "Olá, {$toName}!\n\nRecebemos uma solicitação para redefinir a senha da sua conta.\nPara criar uma nova senha, acesse o link (válido por 1 hora):\n\n{$resetLink}\n\nSe você não solicitou, ignore este e-mail.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail (PHPMailer): {$mail->ErrorInfo}");
            return false;
        }
    }
}
