<?php
require_once __DIR__ . "/../config/base_url.php";
session_start();

include("../config/database.php");
$conexao = conecta();

$mensagem = "";
$tipoMensagem = "";

$nome = "";
$sobrenome = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $sobrenome = trim($_POST["sobrenome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";


    /*
     * VALIDAÇÕES DOS CAMPOS
     */

    if ($nome == "") {

        $mensagem = "O nome é obrigatório.";
        $tipoMensagem = "erro";

    } elseif ($sobrenome == "") {

        $mensagem = "O sobrenome é obrigatório.";
        $tipoMensagem = "erro";

    } elseif ($email == "") {

        $mensagem = "O e-mail é obrigatório.";
        $tipoMensagem = "erro";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mensagem = "Digite um e-mail válido.";
        $tipoMensagem = "erro";

    } elseif ($senha == "") {

        $mensagem = "A senha é obrigatória.";
        $tipoMensagem = "erro";

    } elseif (strlen($senha) < 6) {

        $mensagem = "A senha deve ter pelo menos 6 caracteres.";
        $tipoMensagem = "erro";

    } elseif (!preg_match('/[A-Za-z]/', $senha)) {

        $mensagem = "A senha deve conter pelo menos uma letra.";
        $tipoMensagem = "erro";

    } elseif (!preg_match('/[0-9]/', $senha)) {

        $mensagem = "A senha deve conter pelo menos um número.";
        $tipoMensagem = "erro";

    } elseif ($senha !== trim($senha)) {

        $mensagem = "A senha não pode começar ou terminar com espaço.";
        $tipoMensagem = "erro";

    } else {

        /*
         * JUNTA NOME + SOBRENOME
         */

        $nomeCompleto = $nome . " " . $sobrenome;


        /*
         * VERIFICA SE O E-MAIL JÁ EXISTE
         */

        $sql = "SELECT id_usuario
                FROM usuario
                WHERE LOWER(email) = LOWER(:email)
                AND (excluido IS FALSE OR excluido IS NULL)";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();


        if ($stmt->fetch(PDO::FETCH_ASSOC)) {

            $mensagem = "Este e-mail já está cadastrado.";
            $tipoMensagem = "erro";

        } else {

            /*
             * CRIPTOGRAFA A SENHA
             */

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);


            /*
             * INSERE O USUÁRIO
             */

            $sql = "INSERT INTO usuario
                    (nome, email, senha)
                    VALUES
                    (:nome, :email, :senha)
                    RETURNING id_usuario";

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":nome", $nomeCompleto);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":senha", $senhaHash);


            /*
             * EXECUTA O CADASTRO
             */

            if ($stmt->execute()) {

                /*
                 * PEGA O ID DO USUÁRIO CRIADO
                 */

                $idUsuario = $stmt->fetchColumn();


                /*
                 * CRIA A SESSÃO DO USUÁRIO
                 */

                $_SESSION["sessaoUsuario"] = $idUsuario;


                /*
                 * REDIRECIONA PARA A PÁGINA INICIAL
                 */

                header("Location: " . BASE_URL . "/index.php");
                exit;

            } else {

                $mensagem = "Não foi possível criar a conta.";
                $tipoMensagem = "erro";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="E-commerce - Página de cadastro"
    >

    <title>Criar conta</title>

    <link
        rel="icon"
        type="image/png"
        href="../assets/images/logo.png"
    >

    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/sidebar.css">
    <link rel="stylesheet" href="/assets/css/cadastro.css">

</head>

<body>

    <?php

        require_once __DIR__ . "/../components/header.php";

        require_once __DIR__ . "/../components/sidebar.php";

    ?>


    <div class="cadastro-container">

        <div class="cadastro-card">


            <!-- TÍTULO -->

            <h1>
                Criar conta
            </h1>


            <p class="cadastro-subtitle">
                Cadastre-se com suas informações
            </p>


            <!-- MENSAGEM -->

            <?php if ($mensagem != ""): ?>

                <p class="mensagem <?php echo $tipoMensagem; ?>">

                    <?php echo htmlspecialchars($mensagem); ?>

                </p>

            <?php endif; ?>


            <!-- FORMULÁRIO -->

            <form
                method="POST"
                action="<?= BASE_URL ?>/pages/cadastro.php"
                id="formCadastro"
            >


                <!-- NOME -->

                <div class="campo">

                    <label for="nome">
                        Nome
                    </label>

                    <div class="input-container">

                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            placeholder="Digite seu nome"
                            value="<?php echo htmlspecialchars($nome); ?>"
                            required
                        >

                    </div>

                </div>


                <!-- SOBRENOME -->

                <div class="campo">

                    <label for="sobrenome">
                        Sobrenome
                    </label>

                    <div class="input-container">

                        <input
                            type="text"
                            id="sobrenome"
                            name="sobrenome"
                            placeholder="Digite seu sobrenome"
                            value="<?php echo htmlspecialchars($sobrenome); ?>"
                            required
                        >

                    </div>

                </div>


                <!-- E-MAIL -->

                <div class="campo">

                    <label for="email">
                        E-mail
                    </label>

                    <div class="input-container">

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Digite seu e-mail"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                        >

                    </div>

                </div>


                <!-- SENHA -->

                <div class="campo">

                    <label for="senha">
                        Senha
                    </label>

                    <div class="input-container senha-container">

                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Digite sua senha"
                            minlength="6"
                            required
                        >


                        <!-- ÍCONE MOSTRAR SENHA -->

                        <span
                            class="icone-senha"
                            id="mostrarSenha"
                            role="button"
                            tabindex="0"
                            aria-label="Mostrar senha"
                        >

                            <svg
                                id="iconeOlho"
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >

                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>

                                <path d="M10.73 5.08A10.74 10.74 0 0 1 12 5c7 0 10 7 10 7a18.5 18.5 0 0 1-1.67 2.68"></path>

                                <path d="M6.61 6.61A18.5 18.5 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>

                                <line x1="2" y1="2" x2="22" y2="22"></line>

                            </svg>

                        </span>

                    </div>


                    <!-- FORÇA DA SENHA -->

                    <div class="forca-senha">

                        <div class="barra-forca">

                            <div
                                class="progresso-forca"
                                id="progressoForca"
                            ></div>

                        </div>

                        <span id="textoForca">
                            Digite uma senha
                        </span>

                    </div>


                    <!-- REQUISITOS -->

                    <div class="requisitos-senha">

                        <span id="requisitoTamanho">
                            • Mínimo de 6 caracteres
                        </span>

                        <span id="requisitoLetra">
                            • Pelo menos 1 letra
                        </span>

                        <span id="requisitoNumero">
                            • Pelo menos 1 número
                        </span>

                        <span id="requisitoEspaco">
                            • Não começar ou terminar com espaço
                        </span>

                    </div>

                </div>


                <!-- BOTÃO -->

                <button type="submit">
                    Cadastrar
                </button>


            </form>


            <!-- LOGIN -->

            <div class="login">

                <span>
                    Já tem uma conta?
                </span>

                <a href="<?= BASE_URL ?>/pages/login.php">
                    Entrar
                </a>

            </div>


            <!-- VOLTAR -->

            <a
                href="<?= BASE_URL ?>/index.php"
                class="voltar"
            >
                Voltar para o início
            </a>


        </div>

    </div>


    <?php

        require_once __DIR__ . "/../components/footer.php";

    ?>


    <script>

        const senha = document.getElementById("senha");

        const mostrarSenha = document.getElementById("mostrarSenha");

        const iconeOlho = document.getElementById("iconeOlho");

        const progressoForca = document.getElementById("progressoForca");

        const textoForca = document.getElementById("textoForca");

        const requisitoTamanho =
            document.getElementById("requisitoTamanho");

        const requisitoLetra =
            document.getElementById("requisitoLetra");

        const requisitoNumero =
            document.getElementById("requisitoNumero");

        const requisitoEspaco =
            document.getElementById("requisitoEspaco");


        /*
         * ALTERA O ÍCONE
         */

        function atualizarIconeSenha() {

            if (senha.type === "password") {

                iconeOlho.innerHTML = `
                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>

                    <path d="M10.73 5.08A10.74 10.74 0 0 1 12 5c7 0 10 7 10 7a18.5 18.5 0 0 1-1.67 2.68"></path>

                    <path d="M6.61 6.61A18.5 18.5 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>

                    <line x1="2" y1="2" x2="22" y2="22"></line>
                `;

                mostrarSenha.setAttribute(
                    "aria-label",
                    "Mostrar senha"
                );

            } else {

                iconeOlho.innerHTML = `
                    <path d="M2.06 12.35a1 1 0 0 1 0-.7 10.75 10.75 0 0 1 19.88 0 1 1 0 0 1 0 .7 10.75 10.75 0 0 1-19.88 0"></path>

                    <circle cx="12" cy="12" r="3"></circle>
                `;

                mostrarSenha.setAttribute(
                    "aria-label",
                    "Ocultar senha"
                );

            }

        }


        /*
         * MOSTRAR / OCULTAR SENHA
         */

        mostrarSenha.addEventListener("click", function () {

            if (senha.type === "password") {

                senha.type = "text";

            } else {

                senha.type = "password";

            }

            atualizarIconeSenha();

        });


        /*
         * SUPORTE AO TECLADO
         */

        mostrarSenha.addEventListener("keydown", function (event) {

            if (
                event.key === "Enter" ||
                event.key === " "
            ) {

                event.preventDefault();

                mostrarSenha.click();

            }

        });


        /*
         * FORÇA DA SENHA
         */

        senha.addEventListener("input", function () {

            const valor = senha.value;

            let pontos = 0;


            /*
             * TAMANHO
             */

            if (valor.length >= 6) {

                pontos++;

                requisitoTamanho.classList.add("valido");

            } else {

                requisitoTamanho.classList.remove("valido");

            }


            /*
             * LETRA
             */

            if (/[A-Za-z]/.test(valor)) {

                pontos++;

                requisitoLetra.classList.add("valido");

            } else {

                requisitoLetra.classList.remove("valido");

            }


            /*
             * NÚMERO
             */

            if (/[0-9]/.test(valor)) {

                pontos++;

                requisitoNumero.classList.add("valido");

            } else {

                requisitoNumero.classList.remove("valido");

            }


            /*
             * ESPAÇO NO INÍCIO OU FINAL
             */

            if (
                valor.length > 0 &&
                valor === valor.trim()
            ) {

                pontos++;

                requisitoEspaco.classList.add("valido");

            } else {

                requisitoEspaco.classList.remove("valido");

            }


            /*
             * BARRA DE FORÇA
             */

            progressoForca.style.width =
                (pontos * 25) + "%";


            if (valor.length === 0) {

                progressoForca.style.backgroundColor =
                    "#e2e8f0";

            } else if (pontos <= 1) {

                progressoForca.style.backgroundColor =
                    "#dc2626";

            } else if (pontos <= 3) {

                progressoForca.style.backgroundColor =
                    "#eab308";

            } else {

                progressoForca.style.backgroundColor =
                    "#16a34a";

            }


            /*
             * TEXTO DA FORÇA
             */

            if (valor.length === 0) {

                textoForca.textContent =
                    "Digite uma senha";

            } else if (pontos <= 1) {

                textoForca.textContent =
                    "Senha fraca";

            } else if (pontos === 2) {

                textoForca.textContent =
                    "Senha média";

            } else if (pontos === 3) {

                textoForca.textContent =
                    "Senha boa";

            } else {

                textoForca.textContent =
                    "Senha forte";

            }

        });


        /*
         * VALIDAÇÃO ANTES DO ENVIO
         */

        document
            .getElementById("formCadastro")
            .addEventListener("submit", function (event) {

                const valor = senha.value;


                if (valor.length < 6) {

                    event.preventDefault();

                    senha.focus();

                    return;

                }


                if (!/[A-Za-z]/.test(valor)) {

                    event.preventDefault();

                    senha.focus();

                    return;

                }


                if (!/[0-9]/.test(valor)) {

                    event.preventDefault();

                    senha.focus();

                    return;

                }


                if (valor !== valor.trim()) {

                    event.preventDefault();

                    senha.focus();

                    return;

                }

            });

    </script>

</body>

</html>