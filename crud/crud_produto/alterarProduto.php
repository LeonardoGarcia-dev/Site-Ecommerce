<html>
<body>

    <h1>Alterar Produtos</h1>

    <?php
        include "utilProduto.php";
        $conn = conecta();
        $id = $_GET['id']; // recupera o id
        $varSQL = "SELECT * FROM crudproduto WHERE id = :id";
        $select = $conn->prepare($varSQL);
        $select->bindParam(':id', $id);
        $select->execute();
        $linha = $select->fetch(); // não tem while, é 1 linha

        $id = $linha['id'];
        $produto = $linha['produto'];
        $preco = $linha['preco'];
    ?>

    <form action='updateProduto.php' method='post' 
        enctype="multipart/form-data">

        <input type='hidden' name='id'        value='<?= $id ?>'>
        Produto<br>
        <input type='text'   name='produto'    value='<?= $produto ?>'><br>
        Preço<br>
        <input type='text'   name='preco' value='<?= $preco ?>'><br>
        
        <?php
             if ( file_exists("imagens/cursos/$id.jpg") ) 
                echo "<img src='imagens/cursos/$id.jpg' height=40><br>";                             
             
        ?>

        <input type='submit' value='Salvar'>
    </form>
</body>

</html>