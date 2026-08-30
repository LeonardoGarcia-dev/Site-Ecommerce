<?php
session_start();

include("../config/database.php");
$conexao = conecta();

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT id_usuario, nome, email, senha 
        FROM usuario
        WHERE LOWER(email) = LOWER(:email) 
        AND (excluido IS FALSE OR excluido IS NULL)";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(":email", $usuario);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resultado) {
        $mensagem = "Não encontrado";
    } else {
        if ($senha == $resultado["senha"]) {

            setcookie("usuario", $usuario, time() + (86400 * 30));

            $_SESSION["sessionConectado"] = TRUE;
            $_SESSION["sessionLogin"] = $resultado["nome"];

            header("Location: usuariohome.php");
            exit;

            } else {
            $mensagem = "Senha incorreta";
            }
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
        content="E-commerce - Página inicial">

    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/login.css">

</head>

<body>

    <?php
        require_once __DIR__ . "/../components/header.php";
        require_once __DIR__ . "/../components/sidebar.php";
    ?>

    <div class="login-container">
        <div class="login-card">

            <h1>Bem-vindo!</h1>

            <p class="login-subtitle">
                Entre na sua conta Polaris
            </p>

            <?php
            if ($mensagem != "") {
                echo "<p class='mensagem'>$mensagem</p>";
            }
            ?>

            <form method="POST" action="login.php">

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

<div class="campo">
    <label for="senha">Senha</label>

    <div class="input-container">
        <input
            type="password"
            id="senha"
            name="senha"
            placeholder="Digite sua senha"
            required
        >
    </div>
</div>

                <div class="opcoes-login">
                    <a href="refazersenha.php">Esqueci minha senha</a>
                </div>

                <button type="submit">
                    Entrar
                </button>
            </form>

            <div class="cadastro">
                <span>Não tem uma conta?</span>
                <a href="cadastro.php">Criar conta</a>
            </div>

            <a href="../index.php" class="voltar">
                Voltar para o início
            </a>

        </div>

    </div>
    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script src="/../assets/js/sidebar.js"></script>
</body>
</html>