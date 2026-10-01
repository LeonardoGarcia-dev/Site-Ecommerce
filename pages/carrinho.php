<?php
// -----------------------------------------------------------------------
// carrinho.php
// Arquivo único do carrinho, baseado no "Algoritmo do carrinho" do usuário:
// - o carrinho fica na SESSAO (aqui, $_SESSION['carrinho'])
// - os links da própria grade chamam este arquivo de novo, passando
//   ?operacao=incluir|excluir|remover|fechar e &id_produto=X
// - 'incluir' soma quantidade; 'excluir' decrementa (e remove se chegar a 1,
//   como no algoritmo original); 'fechar' fecha o carrinho
//
// Ao fechar o carrinho, grava a compra no banco: um registro em COMPRA
// e um registro em CAMPO_PRODUTO para cada item, exatamente como o
// admin/relatorio.php (Monitor de vendas) espera para conseguir listar.
// -----------------------------------------------------------------------

require_once __DIR__ . "/../config/database.php";
$conexao = conecta();
// -----------------------------------------------------------------------

// -----------------------------------------------------------------------
// Dados da vitrine, direto aqui no arquivo (sem produtos-mock.php).
// -----------------------------------------------------------------------
$produtos = [

    1 => [
        'id'             => 1,
        'nome'           => 'Óculos Comum',
        'categoria'      => 'comum',
        'personalizavel' => false,
        'preco'          => 9.00,
        'estoque'        => 0,
        'imagens'        => [
            '../assets/images/oculosComum.png',
        ],
        'descricao'      => '"Estilo clássico e versatilidade essencial: o óculos preto perfeito para qualquer ocasião',
        'vendedor'       => 'Polaris Óculos',
    ],

    2 => [
        'id'             => 2,
        'nome'           => 'Óculos Personalizado',
        'categoria'      => 'personalizado',
        'personalizavel' => true,
        'preco'          => 12.00,
        'estoque'        => 0,
        'imagens'        => [
            '../assets/images/oculosPersonalizado.png',
        ],
        'descricao'      => 'Sua personalidade em destaque: o óculos que transforma a sua mensagem no seu maior estilo.',
        'vendedor'       => 'Polaris Óculos',
    ],

];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// -----------------------------------------------------------------------
// COMEÇA AQUI O PROGRAMA
// Recebe parâmetros por GET (link) ou POST (form da página de produto)
// -----------------------------------------------------------------------
$operacao  = $_REQUEST['operacao']  ?? null;
$idProduto = isset($_REQUEST['id_produto']) ? (int) $_REQUEST['id_produto'] : null;

$mensagem = null;

if ($operacao && $idProduto && isset($produtos[$idProduto])) {

    if ($operacao === 'incluir') {

        $quantidade     = isset($_REQUEST['quantidade']) ? max(1, (int) $_REQUEST['quantidade']) : 1;
        $personalizacao = isset($_REQUEST['personalizacao']) ? trim($_REQUEST['personalizacao']) : '';

        if (isset($_SESSION['carrinho'][$idProduto])) {
            // já existe no carrinho - soma a quantidade
            $_SESSION['carrinho'][$idProduto]['qtdade'] += $quantidade;
        } else {
            // vetor vazio -> recebe a primeira quantidade
            $_SESSION['carrinho'][$idProduto] = [
                'qtdade'         => $quantidade,
                'personalizacao' => '',
            ];
        }

        if ($personalizacao !== '') {
            $_SESSION['carrinho'][$idProduto]['personalizacao'] = $personalizacao;
        }

    } elseif ($operacao === 'excluir') {

        if (isset($_SESSION['carrinho'][$idProduto])) {
            if ($_SESSION['carrinho'][$idProduto]['qtdade'] > 1) {
                // decrementa
                $_SESSION['carrinho'][$idProduto]['qtdade']--;
            } else {
                // se tiver apenas 1, retira o produto da sessão
                unset($_SESSION['carrinho'][$idProduto]);
            }
        }

    } elseif ($operacao === 'remover') {

        // remove o item inteiro, independente da quantidade
        unset($_SESSION['carrinho'][$idProduto]);

    }
}

if ($operacao === 'fechar') {

    // Precisa estar logado para finalizar a compra (é o que gera o
    // fk_usuario da tabela COMPRA, usado inclusive no relatório do admin).
    if (!isset($_SESSION['sessaoUsuario']) || $_SESSION['sessaoUsuario'] == '') {
        header("Location: carrinho.php?erroCompra=login");
        exit;
    }

    if (empty($_SESSION['carrinho'])) {
        header("Location: carrinho.php");
        exit;
    }

    try {
        // Por padrão o PDO não lança exceção em erro de SQL (fica em modo
        // silencioso); aqui ligamos as exceções só para esta conexão, pra
        // conseguir dar rollback caso algum item do carrinho não exista
        // (ou não exista mais) na tabela PRODUTO.
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conexao->beginTransaction();

        // 1) COMPRA: um registro por fechamento de carrinho.
        $insereCompra = $conexao->prepare(
            "INSERT INTO compra (data, fk_usuario, status)
             VALUES (NOW(), :fk_usuario, 'reservado')
             RETURNING id_compra"
        );
        $fkUsuario = $_SESSION['sessaoUsuario'];
        $insereCompra->bindParam(":fk_usuario", $fkUsuario);
        $insereCompra->execute();
        $idCompra = $insereCompra->fetchColumn();

        // 2) CAMPO_PRODUTO: um registro por item do carrinho, ligado à
        // compra recém-criada. Preço gravado é o valor no momento da
        // compra (não muda se o produto mudar de preço depois).
        $insereItem = $conexao->prepare(
            "INSERT INTO campo_produto (fk_compra, fk_produto, quantidade, valor_unitario)
             VALUES (:fk_compra, :fk_produto, :quantidade, :valor_unitario)"
        );

        foreach ($_SESSION['carrinho'] as $idItem => $dadosItem) {
            if (!isset($produtos[$idItem])) {
                continue;
            }

            $insereItem->bindValue(":fk_compra", $idCompra, PDO::PARAM_INT);
            $insereItem->bindValue(":fk_produto", $idItem, PDO::PARAM_INT);
            $insereItem->bindValue(":quantidade", $dadosItem['qtdade'], PDO::PARAM_INT);
            $insereItem->bindValue(":valor_unitario", $produtos[$idItem]['preco']);
            $insereItem->execute();
        }

        $conexao->commit();

        $_SESSION['carrinho'] = [];
        header("Location: carrinho.php?fechado=1");
        exit;

    } catch (PDOException $e) {

        if ($conexao->inTransaction()) {
            $conexao->rollBack();
        }

        // Não foi possível gravar a compra (ex: produto do carrinho não
        // está cadastrado na tabela PRODUTO do banco). O carrinho é
        // mantido para o cliente poder tentar de novo.
        header("Location: carrinho.php?erroCompra=1");
        exit;
    }
}

// Depois de tratar uma operação vinda por link (GET), redireciona pra
// limpar o ?operacao=...&id_produto=... da URL
if ($operacao && $_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: carrinho.php");
    exit;
}

// -----------------------------------------------------------------------
// Monta o grid (equivalente à função ATUALIZAGRIDE do algoritmo)
// -----------------------------------------------------------------------
$itensCarrinho = [];
$totalCarrinho = 0;

foreach ($_SESSION['carrinho'] as $idItem => $dadosItem) {
    if (!isset($produtos[$idItem])) {
        continue;
    }

    $produtoItem = $produtos[$idItem];
    $subtotal    = $produtoItem['preco'] * $dadosItem['qtdade'];
    $totalCarrinho += $subtotal;

    $itensCarrinho[] = [
        'id'             => $produtoItem['id'],
        'nome'           => $produtoItem['nome'],
        'imagem'         => $produtoItem['imagens'][0],
        'preco'          => $produtoItem['preco'],
        'qtdade'         => $dadosItem['qtdade'],
        'personalizacao' => $dadosItem['personalizacao'],
        'subtotal'       => $subtotal,
    ];
}

$carrinhoFoiFechado = isset($_GET['fechado']);
$erroCompra = $_GET['erroCompra'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description" content="E-commerce - Carrinho de compras">

    <title>Carrinho | Polaris Óculos</title>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/carrinho.css">
</head>

<body>
<?php
require_once __DIR__ . "/../components/header.php";
require_once __DIR__ . "/../components/sidebar.php";
?>

    <nav class="breadcrumb">
        <a href="../index.php">Início</a> ›
        <span aria-current="true">Carrinho</span>
    </nav>

    <main>
        <section class="cart">
            <div class="container">

                <h1>Meu carrinho</h1>

                <?php if ($carrinhoFoiFechado): ?>
                    <div class="cart-message">
                        Compra fechada com sucesso!
                    </div>
                <?php endif; ?>

                <?php if ($erroCompra === 'login'): ?>
                    <div class="cart-message cart-message-erro">
                        Você precisa <a href="login.php">entrar na sua conta</a> para finalizar a compra.
                    </div>
                <?php elseif ($erroCompra): ?>
                    <div class="cart-message cart-message-erro">
                        Não foi possível finalizar a compra. Tente novamente em instantes.
                    </div>
                <?php endif; ?>

                <?php if (empty($itensCarrinho)): ?>

                    <div class="cart-empty">
                        <p>Seu carrinho está vazio.</p>
                        <a href="produtos.php" class="btn btn-primary">Ver produtos</a>
                    </div>

                <?php else: ?>

                    <div class="cart-grid">

                        <div class="cart-items">

                            <?php foreach ($itensCarrinho as $item): ?>
                                <div class="cart-item">

                                    <a href="produto.php?id=<?php echo $item['id']; ?>" class="cart-item-image">
                                        <img
                                            src="<?php echo htmlspecialchars($item['imagem']); ?>"
                                            alt="<?php echo htmlspecialchars($item['nome']); ?>"
                                        >
                                    </a>

                                    <div class="cart-item-info">
                                        <a href="produto.php?id=<?php echo $item['id']; ?>" class="cart-item-name">
                                            <?php echo htmlspecialchars($item['nome']); ?>
                                        </a>

                                        <?php if (!empty($item['personalizacao'])): ?>
                                            <p class="cart-item-personalizacao">
                                                Personalização: "<?php echo htmlspecialchars($item['personalizacao']); ?>"
                                            </p>
                                        <?php endif; ?>

                                        <a
                                            href="carrinho.php?operacao=remover&id_produto=<?php echo $item['id']; ?>"
                                            class="cart-item-remove"
                                        >
                                            Remover
                                        </a>
                                    </div>

                                    <div class="cart-item-quantity">
                                        <span class="qty-stepper">
                                            <a
                                                href="carrinho.php?operacao=excluir&id_produto=<?php echo $item['id']; ?>"
                                                aria-label="Diminuir quantidade"
                                            >−</a>
                                            <span class="qty-value"><?php echo (int) $item['qtdade']; ?></span>
                                            <a
                                                href="carrinho.php?operacao=incluir&id_produto=<?php echo $item['id']; ?>"
                                                aria-label="Aumentar quantidade"
                                            >+</a>
                                        </span>
                                    </div>

                                    <div class="cart-item-subtotal">
                                        R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?>
                                    </div>

                                </div>
                            <?php endforeach; ?>

                        </div>

                        <aside class="cart-summary">
                            <h2>Resumo</h2>

                            <div class="cart-summary-row">
                                <span>Subtotal</span>
                                <span>R$ <?php echo number_format($totalCarrinho, 2, ',', '.'); ?></span>
                            </div>
                            <div class="cart-summary-row">
                                <span>Frete</span>
                                <span>Grátis</span>
                            </div>
                            <div class="cart-summary-row cart-summary-total">
                                <span>Total</span>
                                <span>R$ <?php echo number_format($totalCarrinho, 2, ',', '.'); ?></span>
                            </div>

                            <a href="carrinho.php?operacao=fechar" class="btn btn-primary cart-checkout">
                                Finalizar compra
                            </a>
                            <a href="produtos.php" class="cart-continue">Continuar comprando</a>
                        </aside>

                    </div>

                <?php endif; ?>

            </div>
        </section>
    </main>

<?php
require_once __DIR__ . "/../components/footer.php";
?>

    <script src="../assets/js/sidebar.js"></script>
</body>
</html>