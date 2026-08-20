<?php


?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta
        name="description"
        content="E-commerce - Página inicial">

    <title>Contato</title>
    <link rel="stylesheet" href="./assets/css/global.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/sidebar.css">

</head>


<body>  
    <?php
        require_once __DIR__ . "/../components/header.php";
        require_once __DIR__ . "/../components/sidebar.php";
    ?>

    <main>

        <section class="contato1">
        <div class="contato1">
            <h1>Entre em contato</h1>
            <p>
                Estamos aqui para ajudar.
            </p>
        </div>
    </section>


    <section class="contato2">
        <div class="contato2">
            <div class="contato-info">
                <h2>Como podemos ajudar?</h2>
                <p>
                    Caso tenha alguma dúvida sobre nossos produtos, pedidos,
                    pagamentos ou entrega, entre em contato através de um
                    dos nossos canais.
                </p>
                <div class="contato-item">
                    <h3>E-mail</h3>
                    <p>xxx@testepolarisoculos.com</p>
                </div>
                <div class="contato-item">
                    <h3>Instagram</h3>
                    <p>@polarisoculos</p>
                </div>
            </div>


    </main>


    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script src="/../assets/js/sidebar.js"></script>
</body>

</html>