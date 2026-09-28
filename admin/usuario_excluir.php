<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

$id_usuario = $_GET["id"];

$sql = "UPDATE usuario SET excluido = TRUE, data_exclusao = CURRENT_TIMESTAMP WHERE id_usuario = :id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(":id", $id_usuario);
$stmt->execute();

header("Location: usuarios.php");
exit;
?>
