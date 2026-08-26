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

    <title>Login</title>
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

        <section class="login">
        <div class="login">
            <h1>Login</h1>
            
            <form action="/../controllers/loginController.php" method="POST">
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="input-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Entrar</button>
        </div>
    </section>

    </main>


    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script src="/../assets/js/sidebar.js"></script>
</body>
</html>