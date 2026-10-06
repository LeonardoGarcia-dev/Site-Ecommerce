<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$estaLogado = (isset($_SESSION["sessaoUsuario"]) and $_SESSION["sessaoUsuario"] != "");

$linkUsuario = $estaLogado
    ? "/pages/perfil.php?id=" . $_SESSION["sessaoUsuario"]
    : "/pages/login.php";

?>

<header>
    <div class="logo">
        <a href="/index.php">
            <img src="/assets/images/logo.png" alt="logo">
        </a>
    </div>

    <nav>
        <ul>
            <li><a href="../index.php">Início</a></li>
            <li><a href="/pages/produtos.php">Produtos</a></li>
            <li><a href="/pages/sobre.php">Sobre Nós</a></li>
            <li><a href="/pages/contato.php">Contato</a></li>
        </ul>
    </nav>

    <div class="icons">

        <a href="/pages/carrinho.php">
            <i data-lucide="shopping-cart"></i>
        </a>

        <a href="<?= $linkUsuario ?>">
            <?php if ($estaLogado): ?>
                <i data-lucide="user-round-check"></i>
            <?php else: ?>
                <i data-lucide="user-round"></i>
            <?php endif; ?>
        </a>

        <a href="#" class="menu-icon">
            <i data-lucide="text-align-justify"></i>
        </a>

    </div>
</header>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>