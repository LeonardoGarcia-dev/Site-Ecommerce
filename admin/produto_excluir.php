<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

$id_produto = $_GET["id"];

$sql = "UPDATE produto SET excluido = TRUE WHERE id_produto = :id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(":id", $id_produto);
$stmt->execute();

header("Location: produtos.php");
exit;
?>
