<?php
ob_start();

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config/util.php";

use Dompdf\Dompdf;

protegeAdmin();

$conexao = conecta();

$datai = isset($_POST["datai"]) ? $_POST["datai"] : date("Y-m-01");
$dataf = isset($_POST["dataf"]) ? $_POST["dataf"] : date("Y-m-d");
$omitirCancelados = isset($_POST["cancelados"]);

ob_clean();
ob_start();

echo "<!DOCTYPE html>
<html lang='pt-br'>
<head>
<meta charset='UTF-8'>
<style>
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #183653; }
    h1 { font-size: 16px; margin: 0 0 4px 0; }
    h3 { font-size: 11px; margin: 0 0 10px 0; font-weight: normal; }
    table { width: 100%; border-collapse: collapse; }
    th { background-color: #082746; color: #ffffff; text-align: left; padding: 5px; }
    td { border-bottom: 1px solid #cccccc; padding: 4px 5px; }
    tr.subtotal td { background-color: #eef2f6; font-weight: bold; }
    p.total { text-align: right; font-size: 12px; font-weight: bold; margin-top: 12px; }
</style>
</head>
<body>
<h1>Monitor de vendas</h1>
<h3>Período de " . date("d/m/Y", strtotime($datai)) . " a " . date("d/m/Y", strtotime($dataf)) .
($omitirCancelados ? " (sem cancelados)" : "") . "</h3>
<table>
<tr>
    <th>Id</th>
    <th>Status</th>
    <th>Data</th>
    <th>Cliente</th>
    <th>Produto</th>
    <th>Qtd</th>
    <th>Valor unit</th>
    <th>Subtotal</th>
</tr>";


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

while ($linha = $select->fetch(PDO::FETCH_ASSOC)) {

    $id_compra = $linha["id_compra"];
    $status = htmlspecialchars($linha["status"]);
    $data = date("d/m/Y", strtotime($linha["data"]));
    $nome = htmlspecialchars($linha["nome"] ?? "");

    $sqlItens = "SELECT produto.nome, compra_produto.quantidade, compra_produto.valor_unitario,
                        compra_produto.quantidade * compra_produto.valor_unitario AS subtotal
                 FROM compra_produto
                 INNER JOIN produto ON compra_produto.fk_produto = produto.id_produto
                 WHERE compra_produto.fk_compra = :id_compra
                 ORDER BY produto.nome";

    $selectItens = $conexao->prepare($sqlItens);
    $selectItens->bindParam(":id_compra", $id_compra);
    $selectItens->execute();
    $itens = $selectItens->fetchAll(PDO::FETCH_ASSOC);

    $totalCompra = 0;
    $primeiroItem = true;

    if (count($itens) > 0) {
        foreach ($itens as $linhaItem) {
            $nomeProduto = htmlspecialchars($linhaItem["nome"]);
            $quantidade = $linhaItem["quantidade"];
            $valorUnitario = number_format($linhaItem["valor_unitario"], 2, ",", ".");
            $subtotal = number_format($linhaItem["subtotal"], 2, ",", ".");

            $totalCompra += $linhaItem["subtotal"];

            echo "<tr>";
            if ($primeiroItem) {
                echo "<td>$id_compra</td><td>$status</td><td>$data</td><td>$nome</td>";
                $primeiroItem = false;
            } else {
                echo "<td colspan='4'></td>";
            }
            echo "<td>$nomeProduto</td>
                  <td>$quantidade</td>
                  <td>R$ $valorUnitario</td>
                  <td>R$ $subtotal</td>
                  </tr>";
        }
    } else {
        echo "<tr>
                <td>$id_compra</td><td>$status</td><td>$data</td><td>$nome</td>
                <td colspan='4'>Nenhum produto</td>
              </tr>";
    }

    $totalGeral += $totalCompra;
    $totalCompraFormatado = number_format($totalCompra, 2, ",", ".");

    echo "<tr class='subtotal'>
            <td colspan='7'>Total do pedido</td>
            <td>R$ $totalCompraFormatado</td>
          </tr>";
}

echo "</table>
<p class='total'>Total do período: R$ " . number_format($totalGeral, 2, ",", ".") . "</p>
</body>
</html>";

$html = ob_get_clean();

$dompdf = new Dompdf();                   
$dompdf->loadHtml($html);               
$dompdf->setPaper('A4', 'portrait');     
$dompdf->render();                         
$dompdf->stream("relatorio_vendas.pdf");   