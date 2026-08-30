<?php
    include "utilEntrada.php";
    $con = conecta();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $varSQL = "INSERT INTO crudentrada (produto, preco) VALUES (:produto, :preco)";
        $insert = $con->prepare($varSQL);
        
        $insert->bindParam(":produto", $_POST['produto']);
        $insert->bindParam(":preco", $_POST['preco']);

        $insert->execute();

        header("Location: produto.php"); 
        exit; 
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Entrada</title>
</head>
<body>
    <h1>Adicionar Nova Entrada</h1>

    <form method="POST" action="insertEntrada.php">
        <label for="nome">Nome do Produto:</label><br>
        <input type="text" id="produto" name="produto" required><br><br>

        <label for="preco">Preço:</label><br>
        <input type="text" id="preco" name="preco" required><br><br>

        <input type="submit" value="Salvar Produto">
        <button type="button" onclick="window.location.href='produto.php'">Cancelar</button>
    </form>
</body>
</html>