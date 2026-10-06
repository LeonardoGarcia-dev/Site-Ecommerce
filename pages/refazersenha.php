<?php
session_start();
include "../config/util.php";

$mensagem = "";

if ($_POST) {
    $conn = conecta();
    $email = $_POST['email'];
    
    $select = $conn->prepare("SELECT nome, senha FROM usuario WHERE email = :email");
    $select->bindParam(':email', $email);
    $select->execute();
    $linha = $select->fetch();
    
    if ($linha) {
        $token = $linha['senha'];

        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $urlPadrao = $protocolo . $_SERVER['HTTP_HOST'] . BASE_URL;
        $urlSite = isset($_SESSION['sessaoSite']) ? $_SESSION['sessaoSite'] : $urlPadrao;

        $html = "<h4>Redefinir sua senha</h4>
                 Clique no link para redefinir sua senha:<br>" . 
                 $urlSite . 
                 "/pages/redefinir.php?token=$token";
         
        $_SESSION['sessaoToken'] = $token;
        $_SESSION[$token] = $email;

        $pUsuario = 'ecommercepolaris5@gmail.com'; 
        $pSenha = 'ycsvbcxraroskloh'; 
        $pSMTP = 'smtp.gmail.com'; 

        if (EnviaEmail($email, 'Recupere a sua senha do ecommerce', $html, $pUsuario, $pSenha, $pSMTP)) {
            $mensagem = "<b>Email enviado com sucesso</b> (verifique sua caixa de spam se nao encontrar)";
        }   
    } else {
        $mensagem = "Email não encontrado.";
    }
}
?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta
        name="description"
        content="E-commerce - Recuperar senha">

    <title>Refazer senha</title>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/login.css">

</head>

<body>

    <?php
        require_once __DIR__ . "/../components/header.php";
        require_once __DIR__ . "/../components/sidebar.php";
    ?>

    <main>

        <div class="login-container">
            <div class="login-card">

                <h1>Esqueceu a senha?</h1>

                <p class="login-subtitle">
                    Enviaremos um link de recuperação para o seu e-mail
                </p>

                <?php
                if ($mensagem != "") {
                    echo "<p class='mensagem'>$mensagem</p>";
                }
                ?>

                <form action="" method="post">

                    <div class="campo">
                        <label for="email">E-mail</label>

                        <div class="input-container">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Digite seu e-mail"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit">
                        Enviar
                    </button>
                </form>

                <div class="cadastro">
                    <span>Lembrou da senha?</span>
                    <a href="login.php">Fazer login</a>
                </div>

                <a href="../index.php" class="voltar">
                    Voltar para o início
                </a>

            </div>
        </div>
    </main>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script src="../assets/js/sidebar.js"></script>
</body>
</html>
