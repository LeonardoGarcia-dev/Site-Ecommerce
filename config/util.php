<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function post(string $key, string $default = ''): string
{
    return trim((string) ($_POST[$key] ?? $default));
}

function SaiSeHacker(): void
{
    if (empty($_SESSION['sessaoAdmin'])) {
        header('Location: /index.php');
        exit;
    }
}

function EnviaEmail(
    string $destino,
    string $assunto,
    string $html,
    string $usuario = '',
    string $senha = '',
    string $smtp = 'smtp.gmail.com'
): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $smtp;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->Username = $usuario ?: (string) getenv('SMTP_USER');
        $mail->Password = $senha ?: (string) getenv('SMTP_PASSWORD');
        $mail->setFrom($mail->Username, 'Suporte Polaris');
        $mail->addAddress($destino);
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $html;
        return $mail->send();
    } catch (Throwable $exception) {
        error_log('Falha no envio de e-mail: ' . $exception->getMessage());
        return false;
    }
}
