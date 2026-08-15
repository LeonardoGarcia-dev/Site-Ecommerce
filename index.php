<?php

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"content="E-commerce - Página inicial">
    <title>Polaris Óculos</title>
    <!-- global css -->
    <link
        rel="stylesheet"
        href="/../assets/css/global.css">
    <!-- header css -->
    <link
        rel="stylesheet"
        href="/../assets/css/header.css">
    <!-- home css -->
    <link
        rel="stylesheet"
        href="/../assets/css/home.css">
    <!-- footer css -->
    <link
        rel="stylesheet"
        href="/../assets/css/footer.css">

    <!-- sidebar css -->
         <link
        rel="stylesheet"
        href="/../assets/css/footer.css">
    <!-- sidebar css -->
        <link
            rel="stylesheet"
            href="/../assets/css/sidebar.css">
</head>

<body>
    <?php
        require_once __DIR__ . "/components/header.php";
        require_once __DIR__ . "/components/sidebar.php";
    ?>
    <main>
        <section class="hero">
            <div class="carrossel-container">
                <div class="carrossel-track">
                    <img src="/../assets/images/oculos1.jpg" alt="Óculos 1" class="slide" />
                    <img src="/../assets/images/oculos2.jpg" alt="Óculos 2" class="slide" />
                    <img src="/../assets/images/oculos3.jpg" alt="Óculos 3" class="slide" />
                </div>
            </div>

            <div class="hero-content">
                <h1>Tudo o que você precisa em um só lugar.</h1>
                <p>Encontre produtos de qualidade, preços especiais e uma experiência de compra simples e segura.</p>
                <a href="pages/produtos.php" class="btn">Ver produtos</a>
            </div>
        </section>

        <section class="categories">
            <div class="container">
                <h2>
                    Compre por categoria
                </h2>
                
                <div class="category-grid">
                    <a href="pages/produtos.php?categoria=comum">
                        <img src="/../assets/images/oculosComum.png" alt="Óculos Comum" />
                        <span>Comum</span>
                    </a>
                    <a href="pages/produtos.php?categoria=personalizado">
                        <img src="/../assets/images/oculosPersonalizado.png" alt="Óculos Personalizado" />
                        <span>Personalizado</span>
                    </a>
                </div>
        </section>
    </main>

    <?php
        require_once __DIR__ . "/components/footer.php";
    ?>
    
    <script src="/../assets/js/sidebar.js"></script>
</body>

</html>
