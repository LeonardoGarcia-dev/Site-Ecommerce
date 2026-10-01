<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

if (isset($_GET["pesquisa"]) && $_GET["pesquisa"] != "") {

    $filtro = "%" . $_GET["pesquisa"] . "%";

    $sql = "SELECT id_usuario, nome, email, admin
            FROM usuario
            WHERE (nome ILIKE :pesquisa OR email ILIKE :pesquisa)
            AND (excluido IS FALSE OR excluido IS NULL)
            ORDER BY id_usuario ASC";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(":pesquisa", $filtro);

} else {

    $sql = "SELECT id_usuario, nome, email, admin
            FROM usuario
            WHERE (excluido IS FALSE OR excluido IS NULL)
            ORDER BY id_usuario ASC";

    $stmt = $conexao->prepare($sql);
}

$stmt->execute();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Usuários - Painel administrativo</title>
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
                <h1>Usuários</h1>
                <a href="usuario_form.php" class="admin-novo">Adicionar usuário</a>
            </div>

            <div class="admin-menu">
                <a href="painel.php">Painel</a>
                <a href="produtos.php">Produtos</a>
                <a href="usuarios.php" class="ativo">Usuários</a>
                <a href="entradas.php">Entradas</a>
                <a href="relatorio.php">Monitor de vendas</a>
            </div>

            <form method="GET" action="usuarios.php" class="admin-busca">
                <input
                    type="text"
                    name="pesquisa"
                    placeholder="Buscar por nome ou e-mail"
                    value="<?= isset($_GET["pesquisa"]) ? htmlspecialchars($_GET["pesquisa"]) : "" ?>"
                >
                <button type="submit">Buscar</button>
            </form>

            <br>

            <table class="admin-tabela">
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Admin</th>
                    <th>Ações</th>
                </tr>

                <?php
                while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $id_usuario = $linha["id_usuario"];
                    $nome = htmlspecialchars($linha["nome"]);
                    $email = htmlspecialchars($linha["email"]);
                    $admin = $linha["admin"] ? "Sim" : "Não";

                    echo "<tr>
                            <td>$id_usuario</td>
                            <td>$nome</td>
                            <td>$email</td>
                            <td>$admin</td>
                            <td class='admin-acoes'>
                                <a href='usuario_form.php?id=$id_usuario'>Editar</a>
                                <a href='usuario_excluir.php?id=$id_usuario' class='excluir'
                                   onclick=\"return confirm('Excluir esse usuário?')\">Excluir</a>
                            </td>
                          </tr>";
                }
                ?>
            </table>

        </div>

    </main>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

</body>
</html>
