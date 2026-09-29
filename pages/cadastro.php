<?php
require_once __DIR__ . "/../config/base_url.php";
session_start();

include("../config/database.php");
$conexao = conecta();

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $sobrenome = trim($_POST["sobrenome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];


    /*
     * JUNTA NOME + SOBRENOME
     *
     * Exemplo:
     * Nome: Leonardo
     * Sobrenome: Cavalcante
     *
     * Resultado:
     * Leonardo Cavalcante
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
         * INSERE O USUÁRIO NO BANCO
         *
         * O banco possui apenas a coluna "nome".
         *
         * Será salvo:
         *
         * Leonardo Cavalcante
         */

        $sql = "INSERT INTO usuario
                (nome, email, senha)
                VALUES
                (:nome, :email, :senha)";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(":nome", $nomeCompleto);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senhaHash);


        /*
         * EXECUTA O CADASTRO
         */

        if ($stmt->execute()) {

            $mensagem = "Conta criada com sucesso!";
            $tipoMensagem = "sucesso";


            /*
             * LIMPA OS CAMPOS
             */

            $nome = "";
            $sobrenome = "";
            $email = "";

        } else {

            $mensagem = "Não foi possível criar a conta.";
            $tipoMensagem = "erro";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta
        name="description"
        content="E-commerce - Página inicial">

    <title>Criar conta</title>
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
                id="formCadastro">


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
                            value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>"
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
                            value="<?php echo isset($sobrenome) ? htmlspecialchars($sobrenome) : ''; ?>"
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
                            value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                            required
                        >

                    </div>

                </div>


                <!-- SENHA -->

                <div class="campo">

                    <label for="senha">
                        Senha
                    </label>

                    <div class="input-container">

                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Digite sua senha"
                            required
                        >

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
                class="voltar">

                Voltar para o início

            </a>


        </div>

    </div>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

</body>
</html>