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

    <title>Sobre nós</title>
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
        href="/../assets/css/sidebar.css">

</head>

<body>  
    <?php
        require_once __DIR__ . "/../components/header.php";
        require_once __DIR__ . "/../components/sidebar.php";
    ?>

    <main>

        <section class="sobre">
        <div class="sobre">
            <h1>Sobre nós</h1>
            <p>
                Em desenvolvimento...
            </p>
        </div>
    </section>
    
    </main>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script src="/../assets/js/sidebar.js"></script>
</body>
</html>