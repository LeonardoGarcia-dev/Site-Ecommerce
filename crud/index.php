<?php
require_once __DIR__ . '/bootstrap.php';
adminHeader('Administração');
?>
<main class="admin-page">
    <h1>Área administrativa</h1>
    <p>Gerencie o catálogo, usuários e entradas de estoque.</p>
    <nav class="admin-nav">
        <a href="produtos.php">Produtos</a><a href="usuarios.php">Usuários</a>
        <a href="entradas.php">Entradas</a><a href="../relatorio.php">Monitor de vendas</a>
    </nav>
</main>
<?php adminFooter(); ?>
