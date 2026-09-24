<?php
session_start();
include "../config/util.php";
?>

<form action='' method='post'>
    Enviar recuperação da senha para<br>
    <input type='email' name='email' required>
    <input type='submit' value='Enviar'>
</form>

<?php
if ($_POST) {
    $conn = conecta();
    $email = $_POST['email'];
    
    $select = $conn->prepare("SELECT nome, senha FROM usuario WHERE email = :email");
    $select->bindParam(':email', $email);
    $select->execute();
    $linha = $select->fetch();
    
    if ($linha) {
        $token = $linha['senha'];

        $urlSite = isset($_SESSION['sessaoSite']) ? $_SESSION['sessaoSite'] : "http://localhost/Site-Ecommerce";

        $html = "<h4>Redefinir sua senha</h4>
                 Clique no link para redefinir sua senha:<br>" . 
                 $urlSite . 
                 "/pages/redefinir.php?token=$token";
         
        $_SESSION['sessaoToken'] = $token;
        $_SESSION[$token] = $email;

        $pUsuario = 'ecommercepolaris5@gmail.com'; 
        $pSenha = 'ycsvbcxraroskloh'; 
        $pSMTP = 'smtp.gmail.com'; 

        if (EnviaEmail($email, 'Recupere a sua senha do ecommerce', $html, $pUsuario, $pSenha, $pSMTP)) {
            echo "<b>Email enviado com sucesso</b> (verifique sua caixa de spam se nao encontrar)";
        }   
    } else {
        echo "Email não encontrado.";
    }
}
?>