<html>
<body>

    <h1>Alterar Usuario</h1>

    <?php
        include "utilUsuario.php";
        $conn = conecta();
        $id = $_GET['id']; // recupera o id
        $varSQL = "SELECT * FROM cruduser WHERE id = :id";
        $select = $conn->prepare($varSQL);
        $select->bindParam(':id', $id);
        $select->execute();
        $linha = $select->fetch(); // não tem while, é 1 linha

        $id = $linha['id'];
        $usuario = $linha['usuario'];
        $sexo = $linha['sexo'];
    ?>

    <form action='updateProduto.php' method='post' 
        enctype="multipart/form-data">

        <input type='hidden' name='id'        value='<?= $id ?>'>
        Usuario<br>
        <input type='text'   name='usuario'    value='<?= $usuario ?>'><br>
        Sexo<br>
        <input type='text'   name='sexo' value='<?= $sexo ?>'><br>
        
        <?php
             if ( file_exists("imagens/cursos/$id.jpg") ) 
                echo "<img src='imagens/cursos/$id.jpg' height=40><br>";                             
             
        ?>

        <input type='submit' value='Salvar'>
    </form>
</body>

</html>