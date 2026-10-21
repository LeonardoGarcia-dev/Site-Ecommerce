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
// e um registro em COMPRA_PRODUTO para cada item, exatamente como o
// admin/relatorio.php (Monitor de vendas) espera para conseguir listar.
// Também subtrai do estoque a quantidade reservada de cada item.
// -----------------------------------------------------------------------

require_once __DIR__ . "/../config/database.php";
$conexao = conecta();
// -----------------------------------------------------------------------

// -----------------------------------------------------------------------
// Dados da vitrine
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
        'descricao'      => 'Estilo clássico e versatilidade essencial: o óculos preto perfeito para qualquer ocasião',
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

// --- PUXANDO PREÇOS E ESTOQUE DO BANCO DE DADOS ---
$sqlProdutos = "SELECT id_produto, valor_unitario, quantidade FROM produto";
$stmt = $conexao->query($sqlProdutos);
$dadosBanco = [];

while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dadosBanco[$linha['id_produto']] = $linha;
}

foreach ($produtos as $key => $prod) {
    $idProd = $prod['id'];
    if (isset($dadosBanco[$idProd])) {
        $produtos[$key]['preco'] = $dadosBanco[$idProd]['valor_unitario'];
        $produtos[$key]['estoque'] = $dadosBanco[$idProd]['quantidade'];
    }
}
// -----------------------------------------------------------------------

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

$erroEstoque = false; // Flag para controlar aviso de estoque excedido

if ($operacao && $idProduto && isset($produtos[$idProduto])) {

    if ($operacao === 'incluir') {

        $quantidade     = isset($_REQUEST['quantidade']) ? max(1, (int) $_REQUEST['quantidade']) : 1;
        $personalizacao = isset($_REQUEST['personalizacao']) ? trim($_REQUEST['personalizacao']) : '';
        
        $estoqueDisponivel = $produtos[$idProduto]['estoque'];
        $qtdAtualNoCarrinho = isset($_SESSION['carrinho'][$idProduto]) ? $_SESSION['carrinho'][$idProduto]['qtdade'] : 0;
        
        // Verifica se a quantidade solicitada vai estourar o estoque
        if (($qtdAtualNoCarrinho + $quantidade) > $estoqueDisponivel) {
            $erroEstoque = true;
            
            // Se o carrinho estava vazio, mas o usuário pediu mais que o estoque de uma vez, 
            // limitamos para o estoque máximo disponível.
            if ($qtdAtualNoCarrinho === 0 && $estoqueDisponivel > 0) {
                $_SESSION['carrinho'][$idProduto] = [
                    'qtdade'         => $estoqueDisponivel,
                    'personalizacao' => $personalizacao,
                ];
            }
        } else {
            // Segue o fluxo normal de adicionar se estiver dentro do limite
            if (isset($_SESSION['carrinho'][$idProduto])) {
                $_SESSION['carrinho'][$idProduto]['qtdade'] += $quantidade;
            } else {
                $_SESSION['carrinho'][$idProduto] = [
                    'qtdade'         => $quantidade,
                    'personalizacao' => '',
                ];
            }

            if ($personalizacao !== '') {
                $_SESSION['carrinho'][$idProduto]['personalizacao'] = $personalizacao;
            }
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

    // Precisa estar logado para finalizar a compra
    if (!isset($_SESSION['sessaoUsuario']) || $_SESSION['sessaoUsuario'] == '') {
        header("Location: carrinho.php?erroCompra=login");
        exit;
    }

    if (empty($_SESSION['carrinho'])) {
        header("Location: carrinho.php");
        exit;
    }

    try {
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexao->beginTransaction();

        // 1) COMPRA: um registro por fechamento de carrinho, já com status 'reservado'
        $insereCompra = $conexao->prepare(
            "INSERT INTO compra (data, fk_usuario, acrescimo_total, sessao, status)
             VALUES (NOW(), :fk_usuario, :acrescimo_total, :sessao, 'reservado')
             RETURNING id_compra"
        );
        $fkUsuario = $_SESSION['sessaoUsuario'];
        $acrescimo = 0;
        $idSessao  = session_id();
        $insereCompra->bindParam(":fk_usuario", $fkUsuario, PDO::PARAM_INT);
        $insereCompra->bindParam(":acrescimo_total", $acrescimo);
        $insereCompra->bindParam(":sessao", $idSessao);
        $insereCompra->execute();
        $idCompra = $insereCompra->fetchColumn();

        // 2) COMPRA_PRODUTO: um registro por item do carrinho
        $insereItem = $conexao->prepare(
            "INSERT INTO compra_produto (fk_compra, fk_produto, quantidade, valor_unitario)
             VALUES (:fk_compra, :fk_produto, :quantidade, :valor_unitario)"
        );

        // 3) ESTOQUE: subtrai a quantidade reservada SOMENTE se ainda houver
        // quantidade suficiente. A condição no WHERE evita estoque negativo
        // mesmo com 2 clientes reservando ao mesmo tempo.
        $atualizaEstoque = $conexao->prepare(
            "UPDATE produto
                SET quantidade = quantidade - :qtd
              WHERE id_produto = :id
                AND quantidade >= :qtd"
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

            $atualizaEstoque->bindValue(":qtd", $dadosItem['qtdade'], PDO::PARAM_INT);
            $atualizaEstoque->bindValue(":id", $idItem, PDO::PARAM_INT);
            $atualizaEstoque->execute();

            // Nenhuma linha afetada = estoque insuficiente -> desfaz tudo
            if ($atualizaEstoque->rowCount() === 0) {
                throw new PDOException("estoque_insuficiente");
            }
        }

        $conexao->commit();

        $_SESSION['carrinho'] = [];
        header("Location: carrinho.php?fechado=1");
        exit;

    } catch (PDOException $e) {

        if ($conexao->inTransaction()) {
            $conexao->rollBack();
        }

        if ($e->getMessage() === 'estoque_insuficiente') {
            header("Location: carrinho.php?erroCompra=estoque");
        } else {
            header("Location: carrinho.php?erroCompra=1");
        }
        exit;
    }
}

// Depois de tratar uma operação vinda por link (GET), redireciona pra
// limpar o ?operacao=...&id_produto=... da URL
if ($operacao && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($erroEstoque) {
        header("Location: carrinho.php?erroEstoque=1");
    } else {
        header("Location: carrinho.php");
    }
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
        'estoque'        => $produtoItem['estoque'], // Passando o estoque
        'qtdade'         => $dadosItem['qtdade'],
        'personalizacao' => $dadosItem['personalizacao'],
        'subtotal'       => $subtotal,
    ];
}

$carrinhoFoiFechado = isset($_GET['fechado']);
$erroCompra = $_GET['erroCompra'] ?? null;
$avisoEstoque = isset($_GET['erroEstoque']) || $erroEstoque; // Detecta erro de limite de estoque
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
    <div class="cart-message__icon">
        <i data-lucide="check-circle-2"></i>
    </div>

    <div class="cart-message__content">
        <h3>Reserva realizada com sucesso!</h3>

        <p>
            Sua reserva foi confirmada. Dirija-se ao local indicado
            dentro do prazo para retirar seu pedido.
        </p>

        <div class="cart-message__info">
            <i data-lucide="clock-3"></i>
            <span>
                Prazo para retirada: <strong>10 minutos</strong>
            </span>
        </div>

        <div class="cart-message__info">
            <i data-lucide="map-pin"></i>
            <span>
                Local de retirada: <strong>Sala 7</strong>
            </span>

            <a
                href="https://www.ubieventos.com.br/viewMap/32o-semana-do-colegio-2026/?local=34"
                target="_blank"
                rel="noopener noreferrer"
                class="cart-message__map-link"
            >
                <i data-lucide="external-link"></i>
                Ver mapa
            </a>
        </div>
    </div>
</div>

                <?php endif; ?>

                <?php if ($avisoEstoque): ?>
                    <div class="cart-message cart-message-erro">
                        <strong>Atenção:</strong> A quantidade solicitada ultrapassa o limite disponível em estoque para um ou mais produtos.
                    </div>
                <?php endif; ?>

                <?php if ($erroCompra === 'login'): ?>
                    <div class="cart-message cart-message-erro">
                        Você precisa <u><a href="login.php">estar logado</a></u> para finalizar a reserva.
                    </div>
                <?php elseif ($erroCompra === 'estoque'): ?>
                    <div class="cart-message cart-message-erro">
                        Algum item do seu carrinho não tem mais estoque suficiente. Revise as quantidades e tente novamente.
                    </div>
                <?php elseif ($erroCompra): ?>
                    <div class="cart-message cart-message-erro">
                        Não foi possível finalizar a reserva. Verifique os itens e tente novamente em instantes.
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
                                            
                                            <?php if ($item['qtdade'] < $item['estoque']): ?>
                                                <a
                                                    href="carrinho.php?operacao=incluir&id_produto=<?php echo $item['id']; ?>"
                                                    aria-label="Aumentar quantidade"
                                                >+</a>
                                            <?php else: ?>
                                                <!-- Desabilita visualmente o botão + se atingir o estoque máximo -->
                                                <span style="opacity: 0.3; cursor: not-allowed; padding: 0 10px;" title="Estoque máximo atingido">+</span>
                                            <?php endif; ?>
                                        </span>
                                        <?php if ($item['qtdade'] >= $item['estoque']): ?>
                                            <small style="display: block; color: #cc0000; font-size: 11px; margin-top: 4px;">Máximo disponível</small>
                                        <?php endif; ?>
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
                                Finalizar Reserva
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

    <script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        lucide.createIcons();
    });
</script>
</body>
</html>