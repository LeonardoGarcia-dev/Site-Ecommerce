<?php
    include "utilEntrada.php";
    $conn = conecta();
    
    $varSQL = "UPDATE crudentrada SET data = :data, produto = :produto, quantidade = :quantidade, custo = :custo, total = :total WHERE id = :id";
    
    $update = $conn->prepare($varSQL);
    $update->bindParam(':data', $_POST['data']);
    $update->bindParam(':produto', $_POST['produto']);
    $update->bindParam(':quantidade', $_POST['quantidade']);
    $update->bindParam(':custo', $_POST['custo']);
    $update->bindParam(':total', $_POST['total']);
    $update->bindParam(':id', $_POST['id']);

    $update->execute();
  
    header("Location: entradas.php");
    exit;
?>