<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

$sql = "SELECT entrada.id_entrada, entrada.quantidade, entrada.valor_unitario, entrada.data, produto.nome
        FROM entrada
        INNER JOIN produto ON entrada.fk_produto = produto.id_produto
        ORDER BY entrada.data DESC";

$stmt = $conexao->prepare($sql);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Entradas - Painel administrativo</title>
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
                <h1>Entradas de estoque</h1>
                <a href="entrada_form.php" class="admin-novo">Adicionar entrada</a>
            </div>

            <div class="admin-menu">
                <a href="produtos.php">Produtos</a>
                <a href="usuarios.php">Usuários</a>
                <a href="entradas.php" class="ativo">Entradas</a>
                <a href="relatorio.php">Monitor de vendas</a>
            </div>

            <table class="admin-tabela">
                <tr>
                    <th>Id</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor unitário</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>

                <?php
                while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $id_entrada = $linha["id_entrada"];
                    $nome = htmlspecialchars($linha["nome"]);
                    $quantidade = $linha["quantidade"];
                    $valor_unitario = number_format($linha["valor_unitario"], 2, ",", ".");
                    $data = date("d/m/Y", strtotime($linha["data"]));

                    echo "<tr>
                            <td>$id_entrada</td>
                            <td>$nome</td>
                            <td>$quantidade</td>
                            <td>R$ $valor_unitario</td>
                            <td>$data</td>
                            <td class='admin-acoes'>
                                <a href='entrada_excluir.php?id=$id_entrada' class='excluir'
                                   onclick=\"return confirm('Excluir essa entrada?')\">Excluir</a>
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
