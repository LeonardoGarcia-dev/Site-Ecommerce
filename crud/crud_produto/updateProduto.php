<?php
    include "utilProduto.php";
    $conn = conecta();
    
    $varSQL = "UPDATE crudproduto SET produto = :produto, preco = :preco WHERE id = :id";
    
    $update = $conn->prepare($varSQL);
    $update->bindParam(':produto', $_POST['produto']);
    $update->bindParam(':preco', $_POST['preco']);
    $update->bindParam(':id', $_POST['id']);

    $update->execute();
  
    header("Location: produto.php");
    exit;
?>