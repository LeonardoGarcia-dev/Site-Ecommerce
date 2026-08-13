<?php
    include "utilUsuario.php";
    $conn = conecta();
    
    $varSQL = "UPDATE cruduser SET usuario = :usuario, sexo = :sexo WHERE id = :id";
    
    $update = $conn->prepare($varSQL);
    $update->bindParam(':usuario', $_POST['usuario']);
    $update->bindParam(':sexo', $_POST['sexo']);
    $update->bindParam(':id', $_POST['id']);

    $update->execute();
  
    header("Location: usuario.php");
    exit;
?>