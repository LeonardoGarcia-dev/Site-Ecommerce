<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de Produtos</title>
</head>
<body>
    <h1>CRUD de Produtos</h1>

    <form method="POST" action="">
        <input type="text" name="pesquisa" placeholder="Buscar por produto...">
        <input type="submit" value="Pesquisar">
    </form>
    
    <br>

        <?php

            include "utilProduto.php";
            $con = conecta();

            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Produto</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>";


            if (isset($_POST['pesquisa']) && $_POST['pesquisa'] != "") {
                $filtro = "%" . $_POST['pesquisa'] . "%";
                
                $varSQL = "SELECT * FROM crudproduto WHERE (produto LIKE :pesquisa OR preco LIKE :pesquisa) ORDER BY id ASC";
                $select = $con->prepare($varSQL);
                $select->bindParam(":pesquisa", $filtro);
            }
            
            else {
                $varSQL = "SELECT * FROM crudproduto ORDER BY id ASC";
                $select = $con->prepare($varSQL);
            }

            $select->execute();

            $contador = 1;

            while ($linha = $select->fetch()) {

                            $id = $linha['id'];
                            $produto = $linha['produto'];
                            $preco = $linha['preco'];

                            echo "<tr>
                                    <td>{$contador}</td>
                                    <td>{$produto}</td>
                                    <td>{$preco}</td>
                                    
                                    <td>
                                        <button onclick=\"window.location.href='alterarProduto.php?id={$id}'\">Editar</button>
                                        <button onclick=\"window.location.href='excluirProduto.php?id={$id}'\">Excluir</button>
                                    </td>
                                </tr>";

                            $contador++;
                        }

                        echo "</table>";
            ?>

        <br>

        <button onclick="window.location.href='insertProduto.php'">Adicionar</button>
</body>
</html>