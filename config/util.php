<?php

// Tenta incluir os ficheiros do PHPMailer
require_once __DIR__ . '/../PHPMailer/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/PHPMailer/src/SMTP.php';

include_once __DIR__ . "/database.php";
require_once __DIR__ . "/base_url.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function protegeAdmin()
{
    $autorizadoAdmin = (isset($_SESSION["sessaoAdmin"]) and $_SESSION["sessaoAdmin"] == true);

    if (!$autorizadoAdmin) {
        header("Location: " . BASE_URL . "/loja4n/index.php");
        exit;
    }
}

function protegeLogin()
{
    $autorizadoLogin = (isset($_SESSION["sessaoUsuario"]) and $_SESSION["sessaoUsuario"] != "");

    if (!$autorizadoLogin) {
        header("Location: " . BASE_URL . "/pages/login.php");
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

function EnviaEmailContato(
    $pNomeCliente,
    $pEmailCliente,
    $pAssunto,
    $pMensagem,
    $pUsuario = "ecommercepolaris5@gmail.com",
    $pSenha = "ycsvbcxraroskloh",
    $pSMTP = "smtp.gmail.com"
) {
    try {

        // Verifica se o PHPMailer está disponível
        if (class_exists('\PHPMailer\PHPMailer\PHPMailer')) {

            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        } elseif (class_exists('PHPMailer')) {

            $mail = new \PHPMailer(true);

        } else {

            die("ERRO: PHPMailer não foi encontrado.");
        }


        // CONFIGURAÇÃO SMTP

        $mail->isSMTP();

        $mail->CharSet = 'UTF-8';

        $mail->Host = $pSMTP;

        $mail->SMTPAuth = true;

        $mail->SMTPSecure = "tls";

        $mail->Port = 587;

        $mail->Username = $pUsuario;

        $mail->Password = $pSenha;


        // REMETENTE

        $mail->setFrom(
            $pUsuario,
            "Polaris E-commerce"
        );


        // DESTINATÁRIO

        $mail->addAddress(
            $pUsuario,
            "Polaris E-commerce"
        );


        // QUEM ENVIOU A MENSAGEM

        $mail->addReplyTo(
            $pEmailCliente,
            $pNomeCliente
        );


        // CONTEÚDO

        $mail->isHTML(true);

        $mail->Subject = "Contato Polaris - " . $pAssunto;


        // Proteção dos dados

        $nome = htmlspecialchars(
            $pNomeCliente,
            ENT_QUOTES,
            'UTF-8'
        );

        $email = htmlspecialchars(
            $pEmailCliente,
            ENT_QUOTES,
            'UTF-8'
        );

        $assunto = htmlspecialchars(
            $pAssunto,
            ENT_QUOTES,
            'UTF-8'
        );

        $mensagem = nl2br(
            htmlspecialchars(
                $pMensagem,
                ENT_QUOTES,
                'UTF-8'
            )
        );


        // HTML do e-mail

        $mail->Body = "

        <div style='
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            color: #183653;
        '>

            <h2 style='color: #082746;'>
                Nova mensagem de contato
            </h2>

            <p>
                Uma nova mensagem foi enviada
                através do site Polaris E-commerce.
            </p>

            <hr>

            <p>
                <strong>Nome:</strong><br>
                {$nome}
            </p>

            <p>
                <strong>E-mail:</strong><br>
                {$email}
            </p>

            <p>
                <strong>Assunto:</strong><br>
                {$assunto}
            </p>

            <p>
                <strong>Mensagem:</strong><br>
                {$mensagem}
            </p>

            <hr>

            <p style='
                color: #718096;
                font-size: 12px;
            '>
                Esta mensagem foi enviada pelo
                formulário de contato do Polaris.
            </p>

        </div>

        ";


        // Versão sem HTML

        $mail->AltBody =
            "Nova mensagem de contato - Polaris\n\n" .
            "Nome: " . $pNomeCliente . "\n" .
            "E-mail: " . $pEmailCliente . "\n" .
            "Assunto: " . $pAssunto . "\n\n" .
            "Mensagem:\n" . $pMensagem;


        // Envia

        return $mail->send();


    } catch (\Exception $e) {

    return false;
    }
}

?>