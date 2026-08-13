<?php
    include ("utilUsuario.php");
    $conn = conecta();
    $id = $_GET['id'];
    $varSQL = "DELETE FROM cruduser WHERE id = :id";

    $delete = $conn->prepare($varSQL);
    $delete->bindParam(':id', $id);

    $delete->execute();

    header("Location: usuario.php"); 
    exit; 
?>