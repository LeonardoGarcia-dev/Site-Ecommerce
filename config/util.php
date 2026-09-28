<?php

// Tenta incluir os ficheiros do PHPMailer
require_once __DIR__ . '/../PHPMailer/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer/src/SMTP.php';

include_once __DIR__ . "/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function protegeAdmin()
{
    $autorizadoAdmin = (isset($_SESSION["sessaoAdmin"]) and $_SESSION["sessaoAdmin"] == true);

    if (!$autorizadoAdmin) {
        header("Location: /index.php");
        exit;
    }
}

function protegeLogin()
{
    $autorizadoLogin = (isset($_SESSION["sessaoUsuario"]) and $_SESSION["sessaoUsuario"] != "");

    if (!$autorizadoLogin) {
        header("Location: /pages/login.php");
        exit;
    }
}

function ValorSQL($paramConn, $paramSQL)
{
    $linha = $paramConn->query($paramSQL)->fetch();

    if ($linha) {
        return $linha[0];
    } else {
        return null;
    }
}

function ExecutaSQL($paramConn, $paramSQL)
{
    $linhas = $paramConn->exec($paramSQL);
    return ($linhas > 0);
}

function GeraToken()
{
    return bin2hex(random_bytes(16));
}

function EnviaEmail(
    $pEmailDestino,
    $pAssunto,
    $pHtml,
    $pUsuario = "ecommercepolaris5@gmail.com",
    $pSenha = "ycsvbcxraroskloh",
    $pSMTP = "smtp.gmail.com"
) {
    try {
        if (class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
            $mail = new \PHPMailer\PHPMailer\PHPMailer();
        } elseif (class_exists('PHPMailer')) {
            $mail = new \PHPMailer();
        } else {
            return false;
        }

        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = $pSMTP;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";

        $mail->SMTPOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        );

        $mail->Port = 587;

        $mail->Username = $pUsuario;
        $mail->Password = $pSenha;
        $mail->From = $pUsuario;
        $mail->FromName = "Polaris Óculos Redefinir Senha";

        $mail->addAddress($pEmailDestino, "Usuário");
        $mail->isHTML(true);
        $mail->Subject = $pAssunto;
        $mail->Body = $pHtml;

        return $mail->send();
    } catch (\Exception $e) {
        return false;
    }
}
?>