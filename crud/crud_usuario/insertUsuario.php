<?php
    include "utilUsuario.php";
    $con = conecta();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $varSQL = "INSERT INTO cruduser (usuario, sexo) VALUES (:usuario, :sexo)";
        $insert = $con->prepare($varSQL);
        
        $insert->bindParam(":usuario", $_POST['usuario']);
        $insert->bindParam(":sexo", $_POST['sexo']);

        $insert->execute();

        header("Location: usuario.php"); 
        exit; 
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuario</title>
</head>
<body>
    <h1>Adicionar Novo Usuario</h1>

    <form method="POST" action="insertProduto.php">
        <label for="nome">Nome do Usuario:</label><br>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="preco">Sexo:</label><br>
        <input type="text" id="sexo" name="sexo" required><br><br>

        <input type="submit" value="Salvar Usuario">
        <button type="button" onclick="window.location.href='usuario.php'">Cancelar</button>
    </form>
</body>
</html>