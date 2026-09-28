<?php
require_once __DIR__ . "/../config/util.php";
protegeLogin();

$conexao = conecta();

/*
 * Só é permitido visualizar/editar o PRÓPRIO perfil.
 * Ignora completamente o id da URL e usa sempre o id da sessão,
 * garantindo que ninguém acesse o perfil de outro usuário pela URL.
 */
$id_usuario = $_SESSION["sessaoUsuario"];

$nome = "";
$email = "";
$telefone = "";
$admin = false;
$mensagem = "";
$tipoMensagem = "";

$sql = "SELECT id_usuario, nome, email, telefone, admin
        FROM usuario
        WHERE id_usuario = :id
        AND (excluido IS FALSE OR excluido IS NULL)";

$stmt = $conexao->prepare($sql);
$stmt->bindParam(":id", $id_usuario);
$stmt->execute();

$linha = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$linha) {
    // conta não existe mais (ou foi excluída) -> encerra a sessão
    header("Location: logout.php");
    exit;
}

$nome = $linha["nome"];
$email = $linha["email"];
$telefone = $linha["telefone"];
$admin = $linha["admin"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $telefone = trim($_POST["telefone"]);
    $senha = $_POST["senha"];

    $sqlEmail = "SELECT id_usuario
                 FROM usuario
                 WHERE LOWER(email) = LOWER(:email)
                 AND id_usuario != :id
                 AND (excluido IS FALSE OR excluido IS NULL)";

    $stmtEmail = $conexao->prepare($sqlEmail);
    $stmtEmail->bindParam(":email", $email);
    $stmtEmail->bindParam(":id", $id_usuario);
    $stmtEmail->execute();

    if ($stmtEmail->fetch(PDO::FETCH_ASSOC)) {

        $mensagem = "Este e-mail já está em uso por outra conta.";
        $tipoMensagem = "erro";

    } else {

        if ($senha != "") {

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "UPDATE usuario
                    SET nome = :nome, email = :email, telefone = :telefone, senha = :senha
                    WHERE id_usuario = :id";

            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":senha", $senhaHash);

        } else {

            $sql = "UPDATE usuario
                    SET nome = :nome, email = :email, telefone = :telefone
                    WHERE id_usuario = :id";

            $stmt = $conexao->prepare($sql);
        }

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":id", $id_usuario);

        if ($stmt->execute()) {

            $_SESSION["sessaoNome"] = $nome;

            $mensagem = "Perfil atualizado com sucesso!";
            $tipoMensagem = "sucesso";

        } else {

            $mensagem = "Não foi possível atualizar o perfil.";
            $tipoMensagem = "erro";
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
        content="E-commerce - Meu perfil">

    <title>Meu perfil</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/perfil.css">

</head>

<body>

    <?php
        require_once __DIR__ . "/../components/header.php";
        require_once __DIR__ . "/../components/sidebar.php";
    ?>

    <main>

        <div class="login-container">
        <div class="login-card">

            <h1>Meu perfil</h1>

            <p class="login-subtitle">
                Gerencie os seus dados da Polaris
            </p>

            <?php if ($admin) { ?>
                <span class="perfil-badge">Administrador</span>
            <?php } ?>

            <?php if ($mensagem != "") { ?>
                <p class="mensagem <?= $tipoMensagem ?>"><?= htmlspecialchars($mensagem) ?></p>
            <?php } ?>

            <form method="POST" action="perfil.php?id=<?= $id_usuario ?>">

                <div class="campo">
                    <label for="nome">Nome</label>

                    <div class="input-container">
                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            placeholder="Digite seu nome"
                            value="<?= htmlspecialchars($nome) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="campo">
                    <label for="email">E-mail</label>

                    <div class="input-container">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Digite seu e-mail"
                            value="<?= htmlspecialchars($email) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="campo">
                    <label for="telefone">Telefone</label>

                    <div class="input-container">
                        <input
                            type="text"
                            id="telefone"
                            name="telefone"
                            placeholder="Digite seu telefone"
                            value="<?= htmlspecialchars($telefone) ?>"
                        >
                    </div>
                </div>

                <div class="campo">
                    <label for="senha">Nova senha (deixe em branco para manter a atual)</label>

                    <div class="input-container">
                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Digite uma nova senha"
                        >
                    </div>
                </div>

                <button type="submit">
                    Salvar alterações
                </button>
            </form>

            <?php if ($admin) { ?>
                <div class="cadastro">
                    <a href="../admin/painel.php">Ir para o painel administrativo</a>
                </div>
            <?php } ?>

            <a href="logout.php" class="voltar">
                Sair da conta
            </a>

        </div>

    </div>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script src="/../assets/js/sidebar.js"></script>
</body>
</html>
