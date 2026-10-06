<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

$datai = isset($_POST["datai"]) ? $_POST["datai"] : date("Y-m-01");
$dataf = isset($_POST["dataf"]) ? $_POST["dataf"] : date("Y-m-d");
$omitirCancelados = isset($_POST["cancelados"]);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Monitor de vendas - Painel administrativo</title>
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
                <h1>Monitor de vendas</h1>
            </div>

            <div class="admin-menu">
                <a href="painel.php">Painel</a>
                <a href="produtos.php">Produtos</a>
                <a href="usuarios.php">Usuários</a>
                <a href="entradas.php">Entradas</a>
                <a href="relatorio.php" class="ativo">Monitor de vendas</a>
            </div>

            <form method="POST" action="relatorio.php" class="admin-form">

                <div class="campo">
                    <label for="datai">Data inicial</label>
                    <input type="date" id="datai" name="datai" value="<?= $datai ?>">
                </div>

                <div class="campo">
                    <label for="dataf">Data final</label>
                    <input type="date" id="dataf" name="dataf" value="<?= $dataf ?>">
                </div>

                <div class="campo">
                    <label>
                        <input type="checkbox" name="cancelados" value="1" style="width:auto" <?= $omitirCancelados ? "checked" : "" ?>>
                        Omitir cancelados
                    </label>
                </div>

                <button type="submit">Filtrar</button>
            </form>

            <br>

            <?php
            $sql = "SELECT compra.id_compra, compra.status, compra.data, usuario.nome
                    FROM compra
                    LEFT JOIN usuario ON compra.fk_usuario = usuario.id_usuario
                    WHERE compra.data::date BETWEEN :datai AND :dataf "
                    . ($omitirCancelados ? " AND compra.status != 'cancelado' " : "")
                    . " ORDER BY compra.data DESC";

            $select = $conexao->prepare($sql);
            $select->bindParam(":datai", $datai);
            $select->bindParam(":dataf", $dataf);
            $select->execute();

            $totalGeral = 0;
            ?>

            <h3>
                Período de <?= date("d/m/Y", strtotime($datai)) ?> a <?= date("d/m/Y", strtotime($dataf)) ?>
            </h3>

            <table class="admin-tabela">
                <tr>
                    <th>Id</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Produto</th>
                    <th>Qtd</th>
                    <th>Valor unit</th>
                    <th>Subtotal</th>
                </tr>

                <?php
                while ($linha = $select->fetch(PDO::FETCH_ASSOC)) {

                    $id_compra = $linha["id_compra"];
                    $status = htmlspecialchars($linha["status"]);
                    $data = date("d/m/Y", strtotime($linha["data"]));
                    $nome = htmlspecialchars($linha["nome"] ?? "");

                    echo "<tr>
                            <td>$id_compra</td>
                            <td>$status</td>
                            <td>$data</td>
                            <td colspan='5'>$nome</td>
                          </tr>";

                    $sqlItens = "SELECT produto.nome, campo_produto.quantidade, campo_produto.valor_unitario,
                                        campo_produto.quantidade * campo_produto.valor_unitario AS subtotal
                                 FROM campo_produto
                                 INNER JOIN produto ON campo_produto.fk_produto = produto.id_produto
                                 WHERE campo_produto.fk_compra = :id_compra
                                 ORDER BY produto.nome";

                    $selectItens = $conexao->prepare($sqlItens);
                    $selectItens->bindParam(":id_compra", $id_compra);
                    $selectItens->execute();

                    $totalCompra = 0;

                    while ($linhaItem = $selectItens->fetch(PDO::FETCH_ASSOC)) {

                        $nomeProduto = htmlspecialchars($linhaItem["nome"]);
                        $quantidade = $linhaItem["quantidade"];
                        $valorUnitario = number_format($linhaItem["valor_unitario"], 2, ",", ".");
                        $subtotal = number_format($linhaItem["subtotal"], 2, ",", ".");

                        $totalCompra += $linhaItem["subtotal"];

                        echo "<tr>
                                <td colspan='4'></td>
                                <td>$nomeProduto</td>
                                <td>$quantidade</td>
                                <td>R$ $valorUnitario</td>
                                <td>R$ $subtotal</td>
                              </tr>";
                    }

                    $totalGeral += $totalCompra;
                    $totalCompraFormatado = number_format($totalCompra, 2, ",", ".");

                    echo "<tr class='admin-subtotal'>
                            <td colspan='7'>Total do pedido</td>
                            <td>R$ $totalCompraFormatado</td>
                          </tr>";
                }
                ?>
            </table>

            <p class="admin-total">
                Total do período: R$ <?= number_format($totalGeral, 2, ",", ".") ?>
            </p>

        </div>

    </main>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

</body>
</html>
