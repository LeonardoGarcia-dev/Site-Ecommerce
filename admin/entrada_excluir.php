<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

$id_entrada = $_GET["id"];

$sql = "DELETE FROM entrada WHERE id_entrada = :id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(":id", $id_entrada);
$stmt->execute();

header("Location: entradas.php");
exit;
?>
