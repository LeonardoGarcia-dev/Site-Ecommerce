<?php

require_once "../config/util.php";

$mensagemSucesso = "";
$mensagemErro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $assunto = trim($_POST["assunto"] ?? "");
    $mensagem = trim($_POST["mensagem"] ?? "");

    // Verifica se todos os campos foram preenchidos
    if (
        empty($nome) ||
        empty($email) ||
        empty($assunto) ||
        empty($mensagem)
    ) {

        $mensagemErro = "Preencha todos os campos.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mensagemErro = "Digite um e-mail válido.";

    } else {

        // Envia a mensagem para o e-mail da Polaris
        $enviado = EnviaEmailContato(
            $nome,
            $email,
            $assunto,
            $mensagem
        );

        if ($enviado) {

            $mensagemSucesso = "Mensagem enviada com sucesso!";

        } else {

            $mensagemErro = "Não foi possível enviar sua mensagem. Tente novamente mais tarde.";

        }
    }
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="E-commerce - Página de contato"
    >

    <title>Contato</title>

    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/contato.css">

</head>


<body>

    <?php
        require_once __DIR__ . "/../components/header.php";
        require_once __DIR__ . "/../components/sidebar.php";
    ?>


    <main class="contato-page">

        <section class="contato-container">

            <!-- TÍTULO -->

            <div class="contato-intro">

                <span class="contato-subtitulo">
                    ENTRE EM CONTATO
                </span>

                <h1>
                    Como podemos ajudar?
                </h1>

                <p>
                    Tem alguma dúvida sobre nossos produtos, pedidos ou entregas?
                    Envie uma mensagem para nossa equipe.
                </p>

            </div>


            <!-- CONTEÚDO -->

            <div class="contato-content">


                <!-- FORMULÁRIO -->

                <div class="contato-form">

                    <h2>
                        Envie uma mensagem
                    </h2>

                    <p class="form-descricao">
                        Preencha os campos abaixo e fale com a equipe Polaris.
                    </p>


                    <!-- MENSAGEM DE SUCESSO -->

                    <?php if (!empty($mensagemSucesso)): ?>

                        <div class="alert alert-success">
                            <?= htmlspecialchars($mensagemSucesso) ?>
                        </div>

                    <?php endif; ?>


                    <!-- MENSAGEM DE ERRO -->

                    <?php if (!empty($mensagemErro)): ?>

                        <div class="alert alert-error">
                            <?= htmlspecialchars($mensagemErro) ?>
                        </div>

                    <?php endif; ?>


                    <form action="" method="POST">


                        <!-- NOME + E-MAIL -->

                        <div class="campo-duplo">

                            <div class="campo">

                                <label for="nome">
                                    Nome
                                </label>

                                <input
                                    type="text"
                                    id="nome"
                                    name="nome"
                                    placeholder="Digite seu nome"
                                    value="<?= htmlspecialchars($nome ?? "") ?>"
                                    required
                                >

                            </div>


                            <div class="campo">

                                <label for="email">
                                    E-mail
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="seuemail@exemplo.com"
                                    value="<?= htmlspecialchars($email ?? "") ?>"
                                    required
                                >

                            </div>

                        </div>


                        <!-- ASSUNTO -->

                        <div class="campo">

                            <label for="assunto">
                                Assunto
                            </label>

                            <select
                                id="assunto"
                                name="assunto"
                                required
                            >

                                <option
                                    value=""
                                    selected
                                    disabled
                                >
                                    Selecione um assunto
                                </option>

                                <option value="Pedido">
                                    Meu pedido
                                </option>

                                <option value="Produto">
                                    Dúvida sobre produto
                                </option>

                                <option value="Pagamento">
                                    Pagamento
                                </option>

                                <option value="Entrega">
                                    Entrega
                                </option>

                                <option value="Troca">
                                    Troca ou devolução
                                </option>

                                <option value="Outro">
                                    Outro assunto
                                </option>

                            </select>

                        </div>


                        <!-- MENSAGEM -->

                        <div class="campo">

                            <label for="mensagem">
                                Mensagem
                            </label>

                            <textarea
                                id="mensagem"
                                name="mensagem"
                                placeholder="Escreva sua mensagem..."
                                required
                            ><?= htmlspecialchars($mensagem ?? "") ?></textarea>

                        </div>


                        <!-- BOTÃO -->

                        <button
                            type="submit"
                            class="btn-enviar"
                        >

                            <i data-lucide="send"></i>

                            Enviar mensagem

                        </button>

                    </form>

                </div>


                <!-- LADO DIREITO -->

                <aside class="contato-info">


                    <div class="info-intro">

                        <div class="info-logo">

                            <img
                                src="../assets/images/logo.png"
                                alt="Polaris"
                            >

                        </div>

                        <h2>
                            Fale com a Polaris
                        </h2>

                        <p>
                            Nossa equipe está pronta para ajudar você.
                        </p>

                    </div>


                    <!-- E-MAIL -->

                    <a
                        href="mailto:ecommercepolaris5@gmail.com"
                        class="info-item"
                    >

                        <div class="info-icon">
                            <i data-lucide="mail"></i>
                        </div>

                        <div>

                            <span>
                                E-mail
                            </span>

                            <strong>
                                ecommercepolaris5@gmail.com
                            </strong>

                        </div>

                    </a>


                    <!-- INSTAGRAM -->

<a href="https://www.instagram.com/polaris_ecommerce/"
   target="_blank"
   rel="noopener noreferrer"
   class="info-item">

    <div class="info-icon">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            width="20"
            height="20"
        >
            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
        </svg>
    </div>

    <div>
        <span>Instagram</span>
        <strong>@polaris_ecommerce</strong>
    </div>

</a>


                    <!-- HORÁRIO -->

                    <div class="info-item">

    <div class="info-icon">
        <i data-lucide="map-pin"></i>
    </div>

    <div>
        <span>Local</span>
        <strong>Semana do Colégio - CTI Bauru</strong>
    </div>

</div>


                    <!-- FRASE -->

                    <div class="info-frase">

                        <i data-lucide="heart"></i>

                        <p>
                            Obrigado por escolher a Polaris.
                        </p>

                    </div>

                </aside>

            </div>

        </section>

    </main>


    <!-- Lucide -->

    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        lucide.createIcons();
    </script>


    <!-- FOOTER -->

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>


    <!-- SIDEBAR -->

    <script src="../assets/js/sidebar.js"></script>

</body>

</html>