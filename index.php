<?php

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Polaris Óculos: óculos comuns e personalizados com a sua mensagem nas lentes.">
    <title>Polaris Óculos</title>
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="stylesheet" href="./assets/css/global.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/sidebar.css">

    <!-- marca que o JS está ativo (os blocos .reveal só ficam escondidos se o JS rodar) -->
    <script>document.documentElement.classList.add('js');</script>
</head>

<body id="topo">
    <?php
        require_once __DIR__ . "/components/header.php";
        require_once __DIR__ . "/components/sidebar.php";
    ?>

    <script src="./assets/js/sidebar.js"></script>

    <main>

        <!-- ================= HERO ================= -->
        <section class="hero">
            <div class="carrossel-container">
                <div class="carrossel-track">
                    <img src="assets/images/oculos1.jpg" alt="Pessoa usando óculos escuros da Polaris" class="slide" />
                    <img src="assets/images/oculos2.jpg" alt="Óculos da Polaris em destaque" class="slide" />                </div>
            </div>

            <div class="hero-content">
                <h1>O modelo de óculos ideal para você</h1>
                <p>Escolha um modelo clássico ou personalize um  com a sua própria mensagem. Estilo, qualidade.</p>

                <div class="hero-actions">
                    <a href="pages/produtos.php" class="btn">Ver produtos</a>
                    <a href="pages/produtos.php?categoria=personalizado" class="btn btn-outline">Personalizar o meu</a>
                </div>
            </div>
        </section>

        <!-- ================= CATEGORIAS ================= -->
        <section class="categories">
            <div class="container">

                <div class="section-head reveal">
                    <h2>Compre por categoria</h2>
                </div>
                <div class="category-grid">
                    <a href="pages/produto.php?id=1" class="reveal">
                        <img src="assets/images/oculosComum.png" alt="Óculos comum preto de armação retangular" loading="lazy" />
                        <span class="cat-nome">Comum</span>
                    </a>

                    <a href="pages/produto.php?id=2" class="reveal">
                        <img src="assets/images/oculosPersonalizadoFrente.png" alt="Óculos personalizado com mensagem nas lentes" loading="lazy" />
                        <span class="cat-nome">Personalizado</span>
                    </a>
                </div>

            </div>
        </section>

        
        <!-- ================= VÍDEO ================= -->
        <section class="video">
            <div class="container video-grid">

                <div class="video-text reveal">
                    <h2>Conheça a Polaris Óculos</h2>
                    <p>
                        Assista ao vídeo ao lado e conheça melhor a Polaris Óculos,
                        nossos produtos e tudo o que temos para oferecer.
                    </p>
                </div>

                <div class="video-wrap reveal">
                    <iframe
                        src="https://www.youtube.com/embed/X4F1Zggw3uU"
                        title="Vídeo da Polaris Óculos"
                        loading="lazy"
                        allowfullscreen>
                    </iframe>
                </div>

            </div>
        </section>

        <!-- ================= CHAMADA FINAL ================= -->
        <section class="cta">
            <div class="container reveal">
                <h2>Pronto para encontrar o seu óculos?</h2>
                <p>Veja os modelos e escolha o que combina com o seu estilo.</p>
                <a href="pages/produtos.php" class="btn">Ver produtos</a>
            </div>
        </section>

    </main>

    <?php
        require_once __DIR__ . "/components/footer.php";
    ?>

    <script src="./assets/js/home.js"></script>
</body>

</html>