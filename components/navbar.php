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
        <a href="/index.php"><img src="/assets/images/logo.png" alt="logo"> </a>
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
            <img src="/assets/images/carrinho.png" alt="Carrinho">
        </a>

        <a href="<?= $linkUsuario ?>">
            <img src="/assets/images/user.png" alt="Usuário">
        </a>



        <a href="#" class="menu-icon">
            <img src="/assets/images/menu.png" alt="Menu">
        </a>
    </div>
</header>
