<?php 
include "../config/util.php";

$mensagem = "";
$sucesso = false;

if ($_POST) {
    $conn = conecta();

    $senha1 = $_POST['senha1'];
    $senha2 = $_POST['senha2'];
    
    $token = $_GET['token'];       
    $email = $_SESSION[$token];

    $senhaCripto = ValorSQL($conn, "SELECT senha FROM usuario WHERE email='$email'");     
    
    if ($senhaCripto <> $token) {
        $mensagem = "Token invalido !!";
    } elseif ($senha1 == $senha2) {
        $novaSenhaCripto = password_hash($senha1, PASSWORD_DEFAULT);         
        ExecutaSQL($conn, "UPDATE usuario SET senha='$novaSenhaCripto' WHERE email='$email'");
        $mensagem = "Senha alterada com sucesso !!";
        $sucesso = true;
    } else {
        $mensagem = "Senhas estão diferentes";
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
        content="E-commerce - Redefinir senha">

    <title>Redefinir senha</title>
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

                <h1>Redefinir senha</h1>

                <p class="login-subtitle">
                    Crie uma nova senha para a sua conta Polaris
                </p>

                <?php
                if ($mensagem != "") {
                    echo "<p class='mensagem'>$mensagem</p>";
                }
                ?>

                <?php if (!$sucesso) { ?>
                <form action="" method="post">

                    <div class="campo">
                        <label for="senha1">Nova senha</label>

                        <div class="input-container">
                            <input
                                type="password"
                                id="senha1"
                                name="senha1"
                                placeholder="Digite a nova senha"
                                required
                            >
                        </div>
                    </div>

                    <div class="campo">
                        <label for="senha2">Confirmar senha</label>

                        <div class="input-container">
                            <input
                                type="password"
                                id="senha2"
                                name="senha2"
                                placeholder="Redigite a nova senha"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit">
                        Alterar
                    </button>
                </form>
                <?php } else { ?>
                <div class="cadastro">
                    <a href="login.php">Ir para o login</a>
                </div>
                <?php } ?>

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
