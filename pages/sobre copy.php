<?php

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta
        name="description"
        content="Polaris Óculos - Conheça nossa história, proposta de valor, identidade e valores.">

    <title>Sobre nós</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">

</head>

<body>  
    <?php
        require_once __DIR__ . "/../components/header.php";
        require_once __DIR__ . "/../components/sidebar.php";
    ?>

    <main>
        <!-- Título Principal -->
        <section class="sobre-intro">
            <h1>Sobre a Polaris</h1>
            <p>A estrela guia do seu estilo na vida noturna.</p>
            <br>
        </section>

        <!-- História e Apresentação da Marca -->
        <section class="sobre-historia">
            <h2>Nossa História</h2>
            <p>
                A Polaris nasceu da fusão entre a atitude da cultura pop e a estética marcante dos eventos e festas.<br>
                Inspirada nas fotos espontâneas com flash estourado, risadas no esquenta e memórias inesquecíveis,<br>
                a marca surgiu com uma missão simples: criar o acessório definitivo para quem quer se destacar.<br>
                Queríamos um visual de impacto, que combinasse o estilo vintage dos óculos retangulares anos 2000<br>
                com a irreverência das frases personalizadas nas lentes. O resultado foi a criação da Polaris:<br>
                a união perfeita de atitude, humor e estilo para quem faz da sua própria presença o ponto alto da festa.
            </p>
            <br>
        </section>

        <!-- Proposta de Valor / Diferencial -->
        <section class="sobre-diferencial">
            <h2>Proposta de Valor</h2>
            <p>
                O que torna nosso produto especial é a entrega focada na experiência e na estética.<br>
                Oferecemos desde os modelos retangulares minimalistas até a linha customizada com frases exclusivas<br>
                em stencil — como "O PAI TÁ ON", "MANDA PIX" ou "EXXQUECE". Feitos em armações leves,<br>
                confortáveis e resistentes, nossos óculos não são apenas proteção ou acessório: são o ponto de<br>
                partida para puxar conversa, garantir as melhores fotos com o pessoal e curtir a noite com máxima atitude.
            </p>
            <br>
        </section>

        <!-- Explicação da Logo e Identidade Visual -->
        <section class="sobre-identidade">
            <h2>Logo e Identidade Visual</h2>
            <p>
                Nossa logo traz como mascote o Urso Polar de óculos escuros — a representação máxima da postura<br>
                "cool", estilosa e inabalável. O nome Polaris remete à Estrela do Norte (representada pelo brilho<br>
                dourado em destaque no topo da logo), o ponto mais reluzente no céu que serve como guia noturno.<br>
                A combinação do tom azul-marinho com o branco e os detalhes em dourado simbolizam o destaque<br>
                único de quem usa Polaris na escuridão da noite.
            </p>
            <br>
        </section>

        <!-- Missão, Visão e Valores -->
        <section class="sobre-mvv">
            <h2>Missão, Visão e Valores</h2>
            
            <article>
                <h3>Missão</h3>
                <p>Entregar estilo, humor e atitude para festas e eventos através de óculos pretos marcantes e personalizáveis.</p>
                <br>
            </article>

            <article>
                <h3>Visão</h3>
                <p>Ser a marca de óculos de festa mais reconhecida e fotografada nos maiores eventos do país.</p>
                <br>
            </article>

            <article>
                <h3>Valores</h3>
                <ul>
                    <li>Autenticidade: Liberdade para se expressar e brincar com estilos e frases.</li>
                    <li>Presença Marcante: O acessório ideal para garantir a melhor foto e visual.</li>
                    <li>Descontração: Moda leve, divertida e pronta para celebrações.</li>
                </ul>
                <br>
            </article>
        </section>
    </main>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script src="../assets/js/sidebar.js"></script>
</body>
</html>