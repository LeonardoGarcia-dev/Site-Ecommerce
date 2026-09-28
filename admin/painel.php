<?php
require_once __DIR__ . "/../config/util.php";
protegeAdmin();

$conexao = conecta();

$totalProdutos = ValorSQL($conexao, "SELECT COUNT(*) FROM produto WHERE (excluido IS FALSE OR excluido IS NULL)");
$totalUsuarios = ValorSQL($conexao, "SELECT COUNT(*) FROM usuario WHERE (excluido IS FALSE OR excluido IS NULL)");
$totalEntradas = ValorSQL($conexao, "SELECT COUNT(*) FROM entrada");
$totalVendas = ValorSQL($conexao, "SELECT COUNT(*) FROM compra");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Painel administrativo</title>
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
                <h1>Painel administrativo</h1>
            </div>

            <div class="admin-menu">
                <a href="painel.php" class="ativo">Painel</a>
                <a href="produtos.php">Produtos</a>
                <a href="usuarios.php">Usuários</a>
                <a href="entradas.php">Entradas</a>
                <a href="relatorio.php">Monitor de vendas</a>
            </div>

            <p>
                Bem-vindo(a), <?= htmlspecialchars($_SESSION["sessaoNome"] ?? "Administrador") ?>.
                Aqui está um resumo geral da loja.
            </p>

            <br>

            <table class="admin-tabela">
                <tr>
                    <th>Indicador</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td><a href="produtos.php">Produtos cadastrados</a></td>
                    <td><?= $totalProdutos ?? 0 ?></td>
                </tr>
                <tr>
                    <td><a href="usuarios.php">Usuários cadastrados</a></td>
                    <td><?= $totalUsuarios ?? 0 ?></td>
                </tr>
                <tr>
                    <td><a href="entradas.php">Entradas de estoque</a></td>
                    <td><?= $totalEntradas ?? 0 ?></td>
                </tr>
                <tr>
                    <td><a href="relatorio.php">Vendas registradas</a></td>
                    <td><?= $totalVendas ?? 0 ?></td>
                </tr>
            </table>

        </div>

    </main>

    <?php
        require_once __DIR__ . "/../components/footer.php";
    ?>

</body>
</html>
