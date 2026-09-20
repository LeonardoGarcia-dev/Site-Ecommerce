<?php
require_once __DIR__ . '/config/util.php';
$message=''; $sent=false;
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=mb_strtolower(post('email')); $stmt=conecta()->prepare('SELECT id_usuario,nome FROM usuario WHERE LOWER(email)=:email AND (excluido IS FALSE OR excluido IS NULL)');$stmt->execute([':email'=>$email]);$user=$stmt->fetch(PDO::FETCH_ASSOC);
    if($user){$token=bin2hex(random_bytes(32));$_SESSION['recuperacao_'.$token]=['email'=>$email,'expira'=>time()+3600];$url=(isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].'/redefinir.php?token='.urlencode($token);$sent=EnviaEmail($email,'Recuperação de senha','Olá '.e($user['nome']).',<br><a href="'.e($url).'">Clique aqui para redefinir sua senha</a>. O link expira em uma hora.');}
    $message='Se o e-mail estiver cadastrado, as instruções foram enviadas.';
}
?><!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Recuperar senha</title><link rel="stylesheet" href="/assets/css/global.css"><link rel="stylesheet" href="/assets/css/admin.css"></head><body><main class="admin-page"><div class="admin-card"><h1>Recuperar senha</h1><?php if($message):?><p class="admin-message"><?=$message?><?php if(!$sent):?> Verifique a configuração de e-mail se necessário.<?php endif;?></p><?php endif;?><form class="admin-form" method="post"><label>E-mail<input type="email" name="email" required></label><button class="admin-button">Enviar instruções</button></form></div></main></body></html>
