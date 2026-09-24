<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$souAdmin = (isset($_SESSION["sessaoAdmin"]) and $_SESSION["sessaoAdmin"] == true);
?>

<div id="sidebar-overlay" class="sidebar-overlay"></div>

<aside id="sidebar" class="sidebar" aria-hidden="true">
    <div class="sidebar-header">
        <span class="sidebar-logo">Polaris Óculos</span>
        <button id="sidebar-close" class="sidebar-close" aria-label="Fechar menu">&times;</button>
    </div>

    <nav class="sidebar-nav">
        <a href="/index.php">Início</a>
        <a href="/pages/produtos.php">Produtos</a>
        <a href="/pages/sobre.php">Sobre Nós</a>
        <a href="/pages/contato.php">Contato</a>
        <a href="/pages/missao.php">Missão, visão e valores</a>
        <a href="/pages/devs.php">Desenvolvedores</a>
        <?php if ($souAdmin) { ?>
        <a href="/admin/produtos.php">Painel administrativo</a>
        <?php } ?>
        <a href="/pages/login.php">Login</a>
    </nav>
</aside>
