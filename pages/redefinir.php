<?php 
include "../config/util.php";
?>

<h3>Redefinir a senha</h3>
<form action='' method='post'>  
    Senha<br>
    <input type='password' name='senha1'><br>
    Redigite a senha<br>
    <input type='password' name='senha2'><br>    
    <input type='submit' value='Alterar'>
</form>

<?php
if ($_POST) {
    $conn = conecta();

    $senha1 = $_POST['senha1'];
    $senha2 = $_POST['senha2'];
    
    $token = $_GET['token'];       
    $email = $_SESSION[$token];

    $senhaCripto = ValorSQL($conn, "SELECT senha FROM usuarios WHERE email='$email'");     
    
    if ($senhaCripto <> $token) {
        echo "<br>Token invalido !!";
        exit;
    }

    if ($senha1 == $senha2) {
        $novaSenhaCripto = password_hash($senha1, PASSWORD_DEFAULT);         
        ExecutaSQL($conn, "UPDATE usuarios SET senha='$novaSenhaCripto' WHERE email='$email'");
        echo "<br>Senha alterada com sucesso !!";
    } else {
        echo "<br>Senhas estão diferentes";
    }

    echo "<br><br><a href='../index.php'>Voltar</a>"; 
} 
?>