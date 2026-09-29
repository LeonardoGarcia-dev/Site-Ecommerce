<?php

// Onde tem os dados dos produtos 
$produtos = [

    1 => [
        'id'             => 1,
        'nome'           => 'Óculos Comum',
        'categoria'      => 'comum',
        'personalizavel' => false,
        'preco'          => 9.00,
        'estoque'        => 0,
        'imagens'        => [
            //fotos
            '/../assets/images/oculosComum.png',
            '/../assets/images/oculosNormalCostas.png',
            '/../assets/images/oculosComumDireita.png',
            '/../assets/images/oculosComumEsquerda.png',
            '/../assets/images/oculosComumModelo.png',
            '/../assets/images/oculosComumModela.png',

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
            '/../assets/images/oculosPersonalizado.png',
        ],
        'descricao'      => 'Sua personalidade em destaque: o óculos que transforma a sua mensagem no seu maior estilo.',
        'vendedor'       => 'Polaris Óculos',
    ],

];

// Qual produto mostrar (vem do link do product-card: /pages/produto.php?id=2)
$idSelecionado = isset($_GET['id']) ? (int) $_GET['id'] : 1;

if (!isset($produtos[$idSelecionado])) {
    // id inválido ou inexistente - cai no primeiro produto da vitrine
    $chaves = array_keys($produtos);
    $idSelecionado = $chaves[0];
}

$produto = $produtos[$idSelecionado];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description" content="E-commerce - Página do produto">

    <title><?php echo htmlspecialchars($produto['nome']); ?> | Polaris Óculos</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/produto.css">
    
</head>

<body>
<?php
require_once __DIR__ . "/../components/header.php";
require_once __DIR__ . "/../components/sidebar.php";
?>

    <nav class="breadcrumb">
        <a href="/../index.php">Início</a> ›
        <a href="produtos.php?categoria=<?php echo urlencode($produto['categoria']); ?>">
            <?php echo htmlspecialchars(ucfirst($produto['categoria'])); ?>
        </a> ›
        <span aria-current="true"><?php echo htmlspecialchars($produto['nome']); ?></span>
    </nav>

    <main>
        <section class="product-detail">
            <div class="container">

                <div class="product-detail-grid">

                    <div class="product-detail-main">

                        <div class="product-gallery">
                            <div class="product-thumbs">
                                <?php foreach ($produto['imagens'] as $indice => $imagem): ?>
                                    <button
                                        type="button"
                                        class="js-thumb <?php echo $indice === 0 ? 'is-active' : ''; ?>"
                                        data-imagem="<?php echo htmlspecialchars($imagem); ?>"
                                    >
                                        <img
                                            src="<?php echo htmlspecialchars($imagem); ?>"
                                            alt="<?php echo htmlspecialchars($produto['nome']); ?> - foto <?php echo $indice + 1; ?>"
                                        >
                                    </button>
                                <?php endforeach; ?>
                            </div>

                            <div class="product-main-image">
                                <img
                                    class="js-main-image"
                                    src="<?php echo htmlspecialchars($produto['imagens'][0]); ?>"
                                    alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                >
                            </div>
                        </div>

                        <span class="product-detail-category">
                            <?php echo htmlspecialchars(ucfirst($produto['categoria'])); ?>
                        </span>
                        <h1 class="product-detail-title">
                            <?php echo htmlspecialchars($produto['nome']); ?>
                        </h1>

                        <div class="product-detail-description">
                            <h2>Descrição</h2>
                            <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
                        </div>

                    </div>

                    <aside class="buybox">
                        <p class="buybox-price">
                            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                        </p>

                        <form action="carrinho.php" method="post">
                            <input type="hidden" name="operacao" value="incluir">
                            <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">

                           

                            <div class="buybox-quantity">
                                <span>Quantidade: <?php echo (int) $produto['estoque']; ?> disponíveis</span>
                                <span class="qty-stepper">
                                    <button type="button" class="js-qty-minus" aria-label="Diminuir quantidade">−</button>
                                    <input
                                        type="text"
                                        class="js-qty-input"
                                        name="quantidade"
                                        value="1"
                                        inputmode="numeric"
                                        aria-label="Quantidade"
                                    >
                                    <button type="button" class="js-qty-plus" aria-label="Aumentar quantidade">+</button>
                                </span>
                            </div>

                            <div class="buybox-actions">
                                <button type="submit" class="btn btn-primary">Comprar agora</button>
                                <button type="submit" class="btn btn-secondary">Adicionar ao carrinho</button>
                            </div>
                        </form>

                        <div class="buybox-seller">
                            <span>Vendido por</span>
                            <strong><?php echo htmlspecialchars($produto['vendedor']); ?></strong>
                        </div>
                    </aside>

                </div>

            </div>
        </section>
    </main>

<?php
require_once __DIR__ . "/../components/footer.php";
?>

    <script src="/../assets/js/sidebar.js"></script>
    <script>
        // Troca da imagem principal ao clicar numa miniatura
        document.querySelectorAll('.js-thumb').forEach(function (botao) {
            botao.addEventListener('click', function () {
                document.querySelector('.js-main-image').src = botao.dataset.imagem;
                document.querySelectorAll('.js-thumb').forEach(function (b) {
                    b.classList.remove('is-active');
                });
                botao.classList.add('is-active');
            });
        });

        // Contador de quantidade
        var qtyInput = document.querySelector('.js-qty-input');
        document.querySelector('.js-qty-minus').addEventListener('click', function () {
            var valor = Math.max(1, parseInt(qtyInput.value || '1', 10) - 1);
            qtyInput.value = valor;
        });
        document.querySelector('.js-qty-plus').addEventListener('click', function () {
            var valor = parseInt(qtyInput.value || '1', 10) + 1;
            qtyInput.value = valor;
        });
    </script>
</body>
</html>