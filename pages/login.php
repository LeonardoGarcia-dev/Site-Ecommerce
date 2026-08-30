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

    <main>

        <section class="login">
        <div class="login">
            <h1>Login</h1>
            <p>
                Em desenvolvimento...
            </p>
        </div>

    </div>
    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script src="/../assets/js/sidebar.js"></script>
</body>
</html>