<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$souAdmin = (isset($_SESSION["sessaoAdmin"]) and $_SESSION["sessaoAdmin"] == true);
$estaLogado = (isset($_SESSION["sessaoUsuario"]) and $_SESSION["sessaoUsuario"] != "");
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
            <a href="/admin/painel.php">Painel administrativo</a>
        <?php } ?>
        
        <?php if ($estaLogado) { ?>
            <a href="/pages/perfil.php?id=<?= $_SESSION["sessaoUsuario"] ?>">Meu perfil</a>
            <a href="/pages/logout.php">Sair</a>
        <?php } 
        
        else { ?>
            <a href="/pages/login.php">Login</a>
        <?php } ?>
    </nav>
</aside>
