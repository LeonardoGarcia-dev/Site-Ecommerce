<?php
    include ("utilProduto.php");
    $conn = conecta();
    $id = $_GET['id'];
    $varSQL = "DELETE FROM crudproduto WHERE id = :id";

    $delete = $conn->prepare($varSQL);
    $delete->bindParam(':id', $id);

    $delete->execute();

    header("Location: produto.php"); 
    exit; 
?>