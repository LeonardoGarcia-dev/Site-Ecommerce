<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de Entradas</title>
</head>
<body>
    <h1>Entrada de Estoques</h1>

    <form method="POST" action="">
        <input type="text" name="pesquisa" placeholder="Buscar por entrada...">
        <input type="submit" value="Pesquisar">
    </form>
    
    <br>

        <?php

            include "utilEntrada.php";
            $con = conecta();

            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Data
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Custo</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>";


            if (isset($_POST['pesquisa']) && $_POST['pesquisa'] != "") {
                $filtro = "%" . $_POST['pesquisa'] . "%";
                
                $varSQL = "SELECT * FROM crudentrada WHERE (produto LIKE :pesquisa OR preco LIKE :pesquisa) ORDER BY id ASC";
                $select = $con->prepare($varSQL);
                $select->bindParam(":pesquisa", $filtro);
            }
            
            else {
                $varSQL = "SELECT * FROM crudentrada ORDER BY id ASC";
                $select = $con->prepare($varSQL);
            }

            $select->execute();

            $contador = 1;

            while ($linha = $select->fetch()) {

                            $id = $linha['id'];
                            $data = $linha['data'];
                            $produto = $linha['produto'];
                            $quantidade = $linha['quantidade'];
                            $custo = $linha['custo'];
                            $total = $linha['total'];
                            

                            echo "<tr>
                                    <td>{$contador}</td>
                                    <td>{$data}</td>
                                    <td>{$produto}</td>
                                    <td>{$quantidade}</td>
                                    <td>{$custo}</td>
                                    <td>{$total}</td>
                                    <td>
                                        <button onclick=\"window.location.href='alterarEntrada.php?id={$id}'\">Editar</button>
                                        <button onclick=\"window.location.href='excluirEntrada.php?id={$id}'\">Excluir</button>
                                    </td>
                                </tr>";

                            $contador++;
                        }

                        echo "</table>";
            ?>

        <br>

        <button onclick="window.location.href='insertEntrada.php'">Adicionar</button>
</body>
</html>