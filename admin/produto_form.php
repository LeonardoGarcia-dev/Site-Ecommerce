<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

$id_produto = isset($_GET["id"]) ? $_GET["id"] : "";
$nome = "";
$descricao = "";
$valor = "";
$quantidade = "";
$mensagem = "";

if ($id_produto != "") {

    $sql = "SELECT id_produto, nome, descricao, valor_unitario, quantidade
            FROM produto
            WHERE id_produto = :id";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(":id", $id_produto);
    $stmt->execute();

    $linha = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($linha) {
        $nome = $linha["nome"];
        $descricao = $linha["descricao"];
        $valor = $linha["valor_unitario"];
        $quantidade = $linha["quantidade"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    $valor = $_POST["valor"];
    $quantidade = $_POST["quantidade"];
    $id_produto = $_POST["id_produto"];

    if ($id_produto == "") {

        $sql = "INSERT INTO produto (nome, descricao, valor_unitario, quantidade)
                VALUES (:nome, :descricao, :valor, :quantidade)";

        $stmt = $conexao->prepare($sql);

    } else {

        $sql = "UPDATE produto
                SET nome = :nome, descricao = :descricao, valor_unitario = :valor, quantidade = :quantidade
                WHERE id_produto = :id";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":id", $id_produto);
    }

    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":descricao", $descricao);
    $stmt->bindParam(":valor", $valor);
    $stmt->bindParam(":quantidade", $quantidade);

    if ($stmt->execute()) {
        header("Location: produtos.php");
        exit;
    } else {
        $mensagem = "Não foi possível salvar o produto.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Produto - Painel administrativo</title>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

    <?php
        require_once __DIR__ . "/../components/header.php";
        require_once __DIR__ . "/../components/sidebar.php";
    ?>

    <main>

        <div class="admin-container">

            <div class="admin-topo">
                <h1><?= $id_produto == "" ? "Adicionar produto" : "Editar produto" ?></h1>
                <a href="produtos.php" class="admin-novo">Voltar</a>
            </div>

            <?php if ($mensagem != "") { ?>
                <p class="mensagem"><?= $mensagem ?></p>
            <?php } ?>

            <form method="POST" action="produto_form.php" class="admin-form">

                <input type="hidden" name="id_produto" value="<?= htmlspecialchars($id_produto) ?>">

                <div class="campo">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <div class="campo">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="4"><?= htmlspecialchars($descricao) ?></textarea>
                </div>

                <div class="campo">
                    <label for="valor">Valor</label>
                    <input type="number" step="0.01" id="valor" name="valor" value="<?= htmlspecialchars($valor) ?>" required>
                </div>

                <div class="campo">
                    <label for="quantidade">Quantidade em estoque</label>
                    <input type="number" id="quantidade" name="quantidade" value="<?= htmlspecialchars($quantidade) ?>" required>
                </div>

                <button type="submit">Salvar</button>
            </form>

        </div>

    </main>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

</body>
</html>
