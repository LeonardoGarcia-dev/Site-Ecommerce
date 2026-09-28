<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

if (isset($_GET["pesquisa"]) && $_GET["pesquisa"] != "") {

    $filtro = "%" . $_GET["pesquisa"] . "%";

    $sql = "SELECT id_produto, nome, valor_unitario, quantidade
            FROM produto
            WHERE nome ILIKE :pesquisa
            AND (excluido IS FALSE OR excluido IS NULL)
            ORDER BY nome";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(":pesquisa", $filtro);

} else {

    $sql = "SELECT id_produto, nome, valor_unitario, quantidade
            FROM produto
            WHERE (excluido IS FALSE OR excluido IS NULL)
            ORDER BY nome";

    $stmt = $conexao->prepare($sql);
}

$stmt->execute();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Produtos - Painel administrativo</title>
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
                <h1>Produtos</h1>
                <a href="produto_form.php" class="admin-novo">Adicionar produto</a>
            </div>

            <div class="admin-menu">
                <a href="painel.php">Painel</a>
                <a href="produtos.php" class="ativo">Produtos</a>
                <a href="usuarios.php">Usuários</a>
                <a href="entradas.php">Entradas</a>
                <a href="relatorio.php">Monitor de vendas</a>
            </div>

            <form method="GET" action="produtos.php" class="admin-busca">
                <input
                    type="text"
                    name="pesquisa"
                    placeholder="Buscar por nome do produto"
                    value="<?= isset($_GET["pesquisa"]) ? htmlspecialchars($_GET["pesquisa"]) : "" ?>"
                >
                <button type="submit">Buscar</button>
            </form>

            <br>

            <table class="admin-tabela">
                <tr>
                    <th>Id</th>
                    <th>Produto</th>
                    <th>Valor</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>

                <?php
                while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $id_produto = $linha["id_produto"];
                    $nome = htmlspecialchars($linha["nome"]);
                    $valor = number_format($linha["valor_unitario"], 2, ",", ".");
                    $quantidade = $linha["quantidade"];

                    echo "<tr>
                            <td>$id_produto</td>
                            <td>$nome</td>
                            <td>R$ $valor</td>
                            <td>$quantidade</td>
                            <td class='admin-acoes'>
                                <a href='produto_form.php?id=$id_produto'>Editar</a>
                                <a href='produto_excluir.php?id=$id_produto' class='excluir'
                                   onclick=\"return confirm('Excluir esse produto?')\">Excluir</a>
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
