<?php
require_once __DIR__ . '/bootstrap.php';
$conn = conecta(); $message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (post('action') === 'delete') {
            $stmt=$conn->prepare('UPDATE usuario SET excluido=TRUE WHERE id_usuario=:id'); $stmt->execute([':id'=>(int)post('id')]);
        } else {
            $id=post('id'); $name=post('nome'); $email=post('email'); $cpf=preg_replace('/\D/','',post('cpf'));
            if (!$name || !filter_var($email,FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException('Nome e e-mail válidos são obrigatórios.');
            if ($id) { $stmt=$conn->prepare('UPDATE usuario SET nome=:nome,email=:email,cpf=:cpf,admin=:admin WHERE id_usuario=:id'); $stmt->execute([':nome'=>$name,':email'=>$email,':cpf'=>$cpf,':admin'=>isset($_POST['admin']),':id'=>(int)$id]); }
            else { $stmt=$conn->prepare('INSERT INTO usuario (nome,email,cpf,senha,admin) VALUES (:nome,:email,:cpf,:senha,:admin)'); $stmt->execute([':nome'=>$name,':email'=>$email,':cpf'=>$cpf,':senha'=>password_hash(post('senha'),PASSWORD_DEFAULT),':admin'=>isset($_POST['admin'])]); }
            header('Location: usuarios.php'); exit;
        }
    } catch(Throwable $exception) { $message=$exception->getMessage(); }
}
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT); $edit=null;
if($id){$stmt=$conn->prepare('SELECT id_usuario,nome,email,cpf,admin FROM usuario WHERE id_usuario=:id');$stmt->execute([':id'=>$id]);$edit=$stmt->fetch(PDO::FETCH_ASSOC);}
$search=post('pesquisa'); $stmt=$conn->prepare('SELECT id_usuario,nome,email,cpf,admin FROM usuario WHERE (excluido IS FALSE OR excluido IS NULL) AND (nome ILIKE :q OR email ILIKE :q) ORDER BY nome');$stmt->execute([':q'=>'%'.$search.'%']);$users=$stmt->fetchAll(PDO::FETCH_ASSOC);
adminHeader('Usuários'); ?>
<main class="admin-page"><h1>Usuários</h1><?php if($message):?><p class="admin-message admin-error"><?=e($message)?></p><?php endif;?>
<div class="admin-card"><form class="admin-form" method="post"><input type="hidden" name="id" value="<?=e($edit['id_usuario']??'')?>"><label>Nome<input name="nome" required value="<?=e($edit['nome']??'')?>"></label><label>E-mail<input type="email" name="email" required value="<?=e($edit['email']??'')?>"></label><label>CPF<input name="cpf" value="<?=e($edit['cpf']??'')?>"></label><?php if(!$edit):?><label>Senha<input type="password" name="senha" required minlength="6"></label><?php endif;?><label><input type="checkbox" name="admin" <?=!empty($edit['admin'])?'checked':''?>> Administrador</label><button class="admin-button">Salvar</button></form></div>
<form class="admin-actions" method="post"><input name="pesquisa" placeholder="Buscar usuário" value="<?=e($search)?>"><button class="admin-button">Pesquisar</button></form><table class="admin-table"><tr><th>ID</th><th>Nome</th><th>E-mail</th><th>ADM</th><th>Ações</th></tr>
<?php foreach($users as $user):?><tr><td><?= (int)$user['id_usuario']?></td><td><?=e($user['nome'])?></td><td><?=e($user['email'])?></td><td><?=!empty($user['admin'])?'Sim':'Não'?></td><td><a href="?id=<?=(int)$user['id_usuario']?>">Editar</a> <form method="post" style="display:inline"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=(int)$user['id_usuario']?>"><button>Excluir</button></form></td></tr><?php endforeach;?></table></main><?php adminFooter(); ?>
