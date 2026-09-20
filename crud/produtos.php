<?php
require_once __DIR__ . '/bootstrap.php';
$conn = conecta();
$message = '';
$edit = null;
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = post('action');
    try {
        if ($action === 'delete') {
            $stmt = $conn->prepare('UPDATE produto SET excluido = TRUE WHERE id_produto = :id');
            $stmt->execute([':id' => (int) post('id')]);
        } elseif ($action === 'save') {
            $name = post('nome'); $price = (float) str_replace(',', '.', post('preco'));
            $description = post('descricao');
            if ($name === '' || $price < 0) throw new InvalidArgumentException('Preencha nome e preço válidos.');
            if (post('id') !== '') {
                $stmt = $conn->prepare('UPDATE produto SET nome=:nome, preco=:preco, descricao=:descricao WHERE id_produto=:id');
                $stmt->execute([':nome'=>$name, ':preco'=>$price, ':descricao'=>$description, ':id'=>(int)post('id')]);
            } else {
                $stmt = $conn->prepare('INSERT INTO produto (nome, preco, descricao) VALUES (:nome,:preco,:descricao)');
                $stmt->execute([':nome'=>$name, ':preco'=>$price, ':descricao'=>$description]);
            }
            header('Location: produtos.php'); exit;
        }
    } catch (Throwable $exception) { $message = $exception->getMessage(); }
}
if ($id) {
    $stmt = $conn->prepare('SELECT id_produto,nome,preco,descricao FROM produto WHERE id_produto=:id');
    $stmt->execute([':id'=>$id]); $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
$search = post('pesquisa');
$stmt = $conn->prepare('SELECT id_produto,nome,preco,descricao FROM produto WHERE (excluido IS FALSE OR excluido IS NULL) AND (nome ILIKE :search OR CAST(preco AS TEXT) ILIKE :search) ORDER BY nome');
$stmt->execute([':search' => '%' . $search . '%']); $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
adminHeader('Produtos');
?>
<main class="admin-page"><h1>Produtos</h1>
<?php if ($message): ?><p class="admin-message admin-error"><?= e($message) ?></p><?php endif; ?>
<div class="admin-card"><form class="admin-form" method="post">
<input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= e($edit['id_produto'] ?? '') ?>">
<label>Nome <input name="nome" required value="<?= e($edit['nome'] ?? '') ?>"></label>
<label>Preço <input name="preco" type="number" step="0.01" min="0" required value="<?= e($edit['preco'] ?? '') ?>"></label>
<label>Descrição <textarea name="descricao"><?= e($edit['descricao'] ?? '') ?></textarea></label>
<button class="admin-button" type="submit"><?= $edit ? 'Atualizar' : 'Adicionar' ?></button>
</form></div>
<form class="admin-actions" method="post"><input name="pesquisa" placeholder="Buscar produto" value="<?= e($search) ?>"><button class="admin-button">Pesquisar</button></form>
<table class="admin-table"><tr><th>ID</th><th>Nome</th><th>Preço</th><th>Ações</th></tr>
<?php foreach ($products as $product): ?><tr><td><?= (int)$product['id_produto'] ?></td><td><?= e($product['nome']) ?></td><td>R$ <?= number_format((float)$product['preco'],2,',','.') ?></td><td class="actions"><a href="?id=<?= (int)$product['id_produto'] ?>">Editar</a> <form method="post" style="display:inline"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$product['id_produto'] ?>"><button type="submit">Excluir</button></form></td></tr><?php endforeach; ?>
</table></main><?php adminFooter(); ?>
