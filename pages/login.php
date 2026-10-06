<?php

session_start();

include("../config/database.php");
$conexao = conecta();

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $sql = "SELECT id_usuario, nome, email, senha, admin
            FROM usuario
            WHERE LOWER(email) = LOWER(:email)
            AND (excluido IS FALSE OR excluido IS NULL)";

    $stmt = $conexao->prepare($sql);

    $stmt->bindParam(":email", $usuario);

    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$resultado) {

        $mensagem = "Não encontrado";

    } else {

        $hashArmazenado = (string) $resultado["senha"];


        /*
         * VERIFICA A SENHA
         */

        $senhaConfere = password_verify(
            $senha,
            $hashArmazenado
        );


        /*
         * COMPATIBILIDADE COM SENHAS ANTIGAS
         */

        if (
            !$senhaConfere &&
            $senha !== "" &&
            hash_equals($hashArmazenado, $senha)
        ) {

            $senhaConfere = true;

            $novoHash = password_hash(
                $senha,
                PASSWORD_DEFAULT
            );

            $atualiza = $conexao->prepare(
                "UPDATE usuario
                 SET senha = :senha
                 WHERE id_usuario = :id"
            );

            $atualiza->bindParam(
                ":senha",
                $novoHash
            );

            $atualiza->bindParam(
                ":id",
                $resultado["id_usuario"]
            );

            $atualiza->execute();
        }


        /*
         * LOGIN
         */

        if ($senhaConfere) {

            setcookie(
                "usuario",
                $usuario,
                time() + (86400 * 30)
            );


            $_SESSION["sessaoUsuario"] =
                $resultado["id_usuario"];

            $_SESSION["sessaoNome"] =
                $resultado["nome"];

            $_SESSION["sessaoAdmin"] =
                $resultado["admin"] ? true : false;


            if ($_SESSION["sessaoAdmin"]) {

                header("Location: ../admin/painel.php");

            } else {

                header(
                    "Location: perfil.php?id=" .
                    $resultado["id_usuario"]
                );

            }

            exit;

        } else {

            $mensagem = "Senha incorreta";

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
        content="E-commerce - Página inicial"
    >

    <title>Login</title>

    <link rel="icon" type="image/png" href="assets/images/logo.png">

    <link
        rel="stylesheet"
        href="../assets/css/global.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/header.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/home.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/footer.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/sidebar.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/login.css"
    >

</head>

<body>


    <?php

        require_once __DIR__ . "/../components/header.php";

        require_once __DIR__ . "/../components/sidebar.php";

    ?>


    <main>

        <div class="login-container">

            <div class="login-card">


                <h1>
                    Bem-vindo!
                </h1>


                <p class="login-subtitle">
                    Entre na sua conta Polaris
                </p>


                <?php

                if ($mensagem != "") {

                    echo "<p class='mensagem'>" .
                         htmlspecialchars($mensagem) .
                         "</p>";

                }

                ?>


                <form
                    method="POST"
                    action="login.php"
                >


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
                                required
                            >


                            <!-- ÍCONE DA SENHA -->

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

                                    <!-- EYE-OFF -->

                                    <path
                                        d="M9.88 9.88a3 3 0 1 0 4.24 4.24"
                                    ></path>

                                    <path
                                        d="M10.73 5.08A10.74 10.74 0 0 1 12 5c7 0 10 7 10 7a18.5 18.5 0 0 1-1.67 2.68"
                                    ></path>

                                    <path
                                        d="M6.61 6.61A18.5 18.5 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"
                                    ></path>

                                    <line
                                        x1="2"
                                        y1="2"
                                        x2="22"
                                        y2="22"
                                    ></line>

                                </svg>

                            </span>

                        </div>

                    </div>


                    <!-- ESQUECI SENHA -->

                    <div class="opcoes-login">

                        <a href="refazersenha.php">
                            Esqueci minha senha
                        </a>

                    </div>


                    <!-- BOTÃO -->

                    <button type="submit">
                        Entrar
                    </button>


                </form>


                <!-- CADASTRO -->

                <div class="cadastro">

                    <span>
                        Não tem uma conta?
                    </span>

                    <a href="cadastro.php">
                        Criar conta
                    </a>

                </div>


                <!-- VOLTAR -->

                <a
                    href="index.php"
                    class="voltar"
                >
                    Voltar para o início
                </a>


            </div>

        </div>

    </main>


    <?php

        require_once __DIR__ . "/../components/footer.php";

    ?>


    <script src="../assets/js/sidebar.js"></script>


    <script>

        const senha =
            document.getElementById("senha");

        const mostrarSenha =
            document.getElementById("mostrarSenha");

        const iconeOlho =
            document.getElementById("iconeOlho");


        /*
         * ALTERA O ÍCONE
         */

        function atualizarIconeSenha() {

            if (senha.type === "password") {

                /*
                 * EYE-OFF
                 */

                iconeOlho.innerHTML = `

                    <path
                        d="M9.88 9.88a3 3 0 1 0 4.24 4.24"
                    ></path>

                    <path
                        d="M10.73 5.08A10.74 10.74 0 0 1 12 5c7 0 10 7 10 7a18.5 18.5 0 0 1-1.67 2.68"
                    ></path>

                    <path
                        d="M6.61 6.61A18.5 18.5 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"
                    ></path>

                    <line
                        x1="2"
                        y1="2"
                        x2="22"
                        y2="22"
                    ></line>

                `;


                mostrarSenha.setAttribute(
                    "aria-label",
                    "Mostrar senha"
                );


            } else {

                /*
                 * EYE
                 */

                iconeOlho.innerHTML = `

                    <path
                        d="M2.06 12.35a1 1 0 0 1 0-.7 10.75 10.75 0 0 1 19.88 0 1 1 0 0 1 0 .7 10.75 10.75 0 0 1-19.88 0"
                    ></path>

                    <circle
                        cx="12"
                        cy="12"
                        r="3"
                    ></circle>

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

        mostrarSenha.addEventListener(
            "click",
            function () {

                if (senha.type === "password") {

                    senha.type = "text";

                } else {

                    senha.type = "password";

                }

                atualizarIconeSenha();

            }
        );


        /*
         * SUPORTE AO TECLADO
         */

        mostrarSenha.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Enter" ||
                    event.key === " "
                ) {

                    event.preventDefault();

                    mostrarSenha.click();

                }

            }
        );

    </script>


</body>

</html>