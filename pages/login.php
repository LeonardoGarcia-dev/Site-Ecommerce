<?php
session_start();

include("../config/database.php");
$conexao = conecta();

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $sql = "SELECT id_usuario, nome, email, senha, admin
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
        $hashArmazenado = (string) $resultado["senha"];

        // Verificação normal: senha gravada com password_hash().
        $senhaConfere = password_verify($senha, $hashArmazenado);

        // Compatibilidade: se o registro foi criado/alterado fora do
        // fluxo do sistema (ex: inserido direto no banco de dados) a
        // senha pode ter ficado gravada em texto puro, sem hash. Nesse
        // caso password_verify() nunca vai bater, mesmo com a senha certa.
        // Aqui comparamos em texto puro como último recurso e, se bater,
        // aproveitamos para já corrigir o registro gravando o hash correto,
        // assim os próximos logins passam a usar password_verify() normalmente.
        if (!$senhaConfere && $senha !== "" && hash_equals($hashArmazenado, $senha)) {
            $senhaConfere = true;

            $novoHash = password_hash($senha, PASSWORD_DEFAULT);
            $atualiza = $conexao->prepare("UPDATE usuario SET senha = :senha WHERE id_usuario = :id");
            $atualiza->bindParam(":senha", $novoHash);
            $atualiza->bindParam(":id", $resultado["id_usuario"]);
            $atualiza->execute();
        }

        if ($senhaConfere) {

            setcookie("usuario", $usuario, time() + (86400 * 30));

            $_SESSION["sessaoUsuario"] = $resultado["id_usuario"];
            $_SESSION["sessaoNome"] = $resultado["nome"];
            $_SESSION["sessaoAdmin"] = $resultado["admin"] ? true : false;

            if ($_SESSION["sessaoAdmin"]) {
                header("Location: ../admin/painel.php");
            } else {
                header("Location: perfil.php?id=" . $resultado["id_usuario"]);
            }
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
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
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

    <main>

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

            <a href="index.php" class="voltar">
                Voltar para o início
            </a>

        </div>

    </div>
        
    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script src="../assets/js/sidebar.js"></script>
</body>
</html>