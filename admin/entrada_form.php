<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

$sql = "SELECT id_produto, nome
        FROM produto
        WHERE (excluido IS FALSE OR excluido IS NULL)
        ORDER BY nome";

$stmt = $conexao->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fk_produto = $_POST["fk_produto"];
    $quantidade = $_POST["quantidade"];
    $valor_unitario = $_POST["valor_unitario"];

    $sql = "INSERT INTO entrada (fk_produto, quantidade, valor_unitario, data)
            VALUES (:fk_produto, :quantidade, :valor_unitario, CURRENT_DATE)";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(":fk_produto", $fk_produto);
    $stmt->bindParam(":quantidade", $quantidade);
    $stmt->bindParam(":valor_unitario", $valor_unitario);

    if ($stmt->execute()) {

        $sql = "UPDATE produto
                SET quantidade = quantidade + :quantidade
                WHERE id_produto = :id";

        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(":quantidade", $quantidade);
        $stmt->bindParam(":id", $fk_produto);
        $stmt->execute();

        header("Location: entradas.php");
        exit;

    } else {
        $mensagem = "Não foi possível registrar a entrada.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Entrada de estoque - Painel administrativo</title>
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
                <h1>Adicionar entrada</h1>
                <a href="entradas.php" class="admin-novo">Voltar</a>
            </div>

            <?php if ($mensagem != "") { ?>
                <p class="mensagem"><?= $mensagem ?></p>
            <?php } ?>

            <form method="POST" action="entrada_form.php" class="admin-form">

                <div class="campo">
                    <label for="fk_produto">Produto</label>
                    <select id="fk_produto" name="fk_produto" required>
                        <?php foreach ($produtos as $produto) { ?>
                            <option value="<?= $produto["id_produto"] ?>">
                                <?= htmlspecialchars($produto["nome"]) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="quantidade">Quantidade</label>
                    <input type="number" id="quantidade" name="quantidade" min="1" required>
                </div>

                <div class="campo">
                    <label for="valor_unitario">Valor unitário</label>
                    <input type="number" step="0.01" id="valor_unitario" name="valor_unitario" required>
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
