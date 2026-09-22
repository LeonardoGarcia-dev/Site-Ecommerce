<?php
session_start();

include("../config/database.php");
$conexao = conecta();

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $sobrenome = trim($_POST["sobrenome"]);
    $email = trim($_POST["email"]);
    $cpf = trim($_POST["cpf"]);
    $senha = $_POST["senha"];


    /*
     * REMOVE PONTOS E HÍFEN DO CPF
     *
     * Exemplo:
     * 123.456.789-09
     *
     * vira:
     * 12345678909
     */

    $cpf = preg_replace('/\D/', '', $cpf);


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
         * VERIFICA SE O CPF JÁ EXISTE
         */

        $sql = "SELECT id_usuario
                FROM usuario
                WHERE cpf = :cpf
                AND (excluido IS FALSE OR excluido IS NULL)";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":cpf", $cpf);
        $stmt->execute();


        if ($stmt->fetch(PDO::FETCH_ASSOC)) {

            $mensagem = "Este CPF já está cadastrado.";
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
                    (nome, email, cpf, senha)
                    VALUES
                    (:nome, :email, :cpf, :senha)";

            $stmt = $conexao->prepare($sql);

            $stmt->bindParam(":nome", $nomeCompleto);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":cpf", $cpf);
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
                $cpf = "";

            } else {

                $mensagem = "Não foi possível criar a conta.";
                $tipoMensagem = "erro";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta
        name="description"
        content="E-commerce - Página inicial">

    <title>Criar conta</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/cadastro.css">
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
                action="cadastro.php"
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


                <!-- CPF -->

                <div class="campo">

                    <label for="cpf">
                        CPF
                    </label>

                    <div class="input-container">

                        <input
                            type="text"
                            id="cpf"
                            name="cpf"
                            placeholder="000.000.000-00"
                            maxlength="14"
                            value="<?php echo isset($cpf) ? htmlspecialchars($cpf) : ''; ?>"
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

                <a href="login.php">
                    Entrar
                </a>

            </div>


            <!-- VOLTAR -->

            <a
                href="../index.php"
                class="voltar">

                Voltar para o início

            </a>


        </div>

    </div>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

    <script>

        /*
         * FUNÇÃO PARA VALIDAR CPF
         */

        function TestaCPF(strCPF) {

            var Soma;
            var Resto;

            Soma = 0;


            /*
             * REJEITA CPF 00000000000
             */

            if (strCPF == "00000000000") {
                return false;
            }


            /*
             * PRIMEIRO DÍGITO
             */

            for (var i = 1; i <= 9; i++) {

                Soma = Soma +
                    parseInt(strCPF.substring(i - 1, i)) * (11 - i);

            }

            Resto = (Soma * 10) % 11;


            if (Resto == 10 || Resto == 11) {
                Resto = 0;
            }


            if (Resto != parseInt(strCPF.substring(9, 10))) {
                return false;
            }


            /*
             * SEGUNDO DÍGITO
             */

            Soma = 0;

            for (var i = 1; i <= 10; i++) {

                Soma = Soma +
                    parseInt(strCPF.substring(i - 1, i)) * (12 - i);

            }

            Resto = (Soma * 10) % 11;


            if (Resto == 10 || Resto == 11) {
                Resto = 0;
            }


            if (Resto != parseInt(strCPF.substring(10, 11))) {
                return false;
            }


            return true;
        }


        /*
         * MÁSCARA DO CPF
         *
         * Transforma:
         *
         * 12345678909
         *
         * em:
         *
         * 123.456.789-09
         */

        document
            .getElementById("cpf")
            .addEventListener("input", function () {

                var cpf = this.value;

                /*
                 * Remove tudo que não for número
                 */

                cpf = cpf.replace(/\D/g, "");


                /*
                 * Limita a 11 números
                 */

                cpf = cpf.substring(0, 11);


                /*
                 * Aplica os pontos
                 */

                if (cpf.length > 3) {

                    cpf =
                        cpf.substring(0, 3)
                        + "."
                        + cpf.substring(3);

                }


                if (cpf.length > 7) {

                    cpf =
                        cpf.substring(0, 7)
                        + "."
                        + cpf.substring(7);

                }


                /*
                 * Aplica o hífen
                 */

                if (cpf.length > 11) {

                    cpf =
                        cpf.substring(0, 11)
                        + "-"
                        + cpf.substring(11);

                }


                this.value = cpf;

            });


        /*
         * VALIDA CPF ANTES DE ENVIAR
         */

        document
            .getElementById("formCadastro")
            .addEventListener("submit", function (event) {

                var cpf =
                    document.getElementById("cpf").value;


                /*
                 * Remove pontos e hífen
                 */

                cpf = cpf.replace(/\D/g, "");


                /*
                 * Verifica se possui 11 números
                 */

                if (cpf.length !== 11) {

                    event.preventDefault();

                    alert("Digite um CPF com 11 números.");

                    return;

                }


                /*
                 * Verifica se o CPF é válido
                 */

                if (!TestaCPF(cpf)) {

                    event.preventDefault();

                    alert("O CPF informado é inválido.");

                    return;

                }

            });

    </script>
</body>
</html>