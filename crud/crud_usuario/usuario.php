<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de Usuarios</title>
</head>
<body>
    <h1>CRUD de Usuario</h1>

    <form method="POST" action="">
        <input type="text" name="pesquisa" placeholder="Buscar por usuario...">
        <input type="submit" value="Pesquisar">
    </form>
    
    <br>

        <?php

            include "utilUsuario.php";
            $con = conecta();

            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Usuario</th>
                        <th>Sexo</th>
                        <th>Ações</th>
                    </tr>";


            if (isset($_POST['pesquisa']) && $_POST['pesquisa'] != "") {
                $filtro = "%" . $_POST['pesquisa'] . "%";
                
                $varSQL = "SELECT * FROM cruduser WHERE (produto LIKE :pesquisa OR sexo LIKE :pesquisa) ORDER BY id ASC";
                $select = $con->prepare($varSQL);
                $select->bindParam(":pesquisa", $filtro);
            }
            
            else {
                $varSQL = "SELECT * FROM cruduser ORDER BY id ASC";
                $select = $con->prepare($varSQL);
            }

            $select->execute();

            $contador = 1;

            while ($linha = $select->fetch()) {

                            $id = $linha['id'];
                            $usuario = $linha['usuario'];
                            $sexo = $linha['sexo'];

                            echo "<tr>
                                    <td>{$contador}</td>
                                    <td>{$usuario}</td>
                                    <td>{$sexo}</td>
                                    
                                    <td>
                                        <button onclick=\"window.location.href='alterarUsuario.php?id={$id}'\">Editar</button>
                                        <button onclick=\"window.location.href='excluirUsuario.php?id={$id}'\">Excluir</button>
                                    </td>
                                </tr>";

                            $contador++;
                        }

                        echo "</table>";
            ?>

        <br>

        <button onclick="window.location.href='insertUsuario.php'">Adicionar</button>
</body>
</html>