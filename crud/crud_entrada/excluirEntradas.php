<?php
    include ("utilEntrada.php");
    $conn = conecta();
    $id = $_GET['id'];
    $varSQL = "DELETE FROM crudentrada WHERE id = :id";

    $delete = $conn->prepare($varSQL);
    $delete->bindParam(':id', $id);

    $delete->execute();

    header("Location: entradas.php"); 
    exit; 
?>