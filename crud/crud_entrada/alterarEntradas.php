<html>
<body>

    <h1>Alterar Produtos</h1>

    <?php
        include "utilEntrada.php";
        $conn = conecta();
        $id = $_GET['id']; // recupera o id
        $varSQL = "SELECT * FROM crudentrada WHERE id = :id";
        $select = $conn->prepare($varSQL);
        $select->bindParam(':id', $id);
        $select->execute();
        $linha = $select->fetch(); // não tem while, é 1 linha

        $id = $linha['id'];
        $data = $linha['data'];
        $produto = $linha['produto'];
        $quantidade = $linha['quantidade'];
        $custo = $linha['custo'];
        $total = $linha['total'];
    ?>

    <form action='updateEntrada.php' method='post' 
        enctype="multipart/form-data">

        <input type='hidden' name='id'        value='<?= $id ?>'>
        Produto<br>
        <input type='text'   name='produto'    value='<?= $produto ?>'><br>
        Quantidade<br>
        <input type='text'   name='quantidade' value='<?= $quantidade ?>'><br>
        Custo<br>
        <input type='text'   name='custo' value='<?= $custo ?>'><br>
        Total<br>
        <input type='text'   name='total' value='<?= $total ?>'><br>

        <?php
             if ( file_exists("imagens/cursos/$id.jpg") ) 
                echo "<img src='imagens/cursos/$id.jpg' height=40><br>";                             
             
        ?>

        <input type='submit' value='Salvar'>
    </form>
</body>

</html>