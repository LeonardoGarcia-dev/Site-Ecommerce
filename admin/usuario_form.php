<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

$id_usuario = isset($_GET["id"]) ? $_GET["id"] : "";
$nome = "";
$email = "";
$telefone = "";
$admin = false;
$mensagem = "";

if ($id_usuario != "") {

    $sql = "SELECT id_usuario, nome, email, telefone, admin
            FROM usuario
            WHERE id_usuario = :id";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(":id", $id_usuario);
    $stmt->execute();

    $linha = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($linha) {
        $nome = $linha["nome"];
        $email = $linha["email"];
        $telefone = $linha["telefone"];
        $admin = $linha["admin"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_usuario = $_POST["id_usuario"];
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $telefone = trim($_POST["telefone"]);
    $senha = $_POST["senha"];
    $admin = isset($_POST["admin"]) ? true : false;

    if ($id_usuario == "") {

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (nome, email, telefone, senha, admin)
                VALUES (:nome, :email, :telefone, :senha, :admin)";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":senha", $senhaHash);

    } else {

        if ($senha != "") {

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "UPDATE usuario
                    SET nome = :nome, email = :email, telefone = :telefone, senha = :senha, admin = :admin
                    WHERE id_usuario = :id";

            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":senha", $senhaHash);

        } else {

            $sql = "UPDATE usuario
                    SET nome = :nome, email = :email, telefone = :telefone, admin = :admin
                    WHERE id_usuario = :id";

            $stmt = $conexao->prepare($sql);
        }

        $stmt->bindParam(":id", $id_usuario);
    }

    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":telefone", $telefone);
    $stmt->bindParam(":admin", $admin, PDO::PARAM_BOOL);

    if ($stmt->execute()) {
        header("Location: usuarios.php");
        exit;
    } else {
        $mensagem = "Não foi possível salvar o usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Usuário - Painel administrativo</title>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

    <?php
        require_once __DIR__ . "/../components/header.php";
        require_once __DIR__ . "/../components/sidebar.php";
    ?>

    <main>

        <div class="admin-container">

            <div class="admin-topo">
                <h1><?= $id_usuario == "" ? "Adicionar usuário" : "Editar usuário" ?></h1>
                <a href="usuario.php" class="admin-novo">Voltar</a>
            </div>

            <?php if ($mensagem != "") { ?>
                <p class="mensagem"><?= $mensagem ?></p>
            <?php } ?>

            <form method="POST" action="usuario_form.php" class="admin-form">

                <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario) ?>">

                <div class="campo">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <div class="campo">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="campo">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($telefone) ?>">
                </div>

                <div class="campo">
                    <label for="senha"><?= $id_usuario == "" ? "Senha" : "Nova senha (deixe em branco para manter a atual)" ?></label>
                    <input type="password" id="senha" name="senha" <?= $id_usuario == "" ? "required" : "" ?>>
                </div>

                <div class="campo">
                    <label>
                        <input type="checkbox" name="admin" value="1" style="width:auto" <?= $admin ? "checked" : "" ?>>
                        Usuário administrador
                    </label>
                </div>

                <button type="submit">Salvar</button>
            </form>

        </div>

    </main>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

</body>
</html>
