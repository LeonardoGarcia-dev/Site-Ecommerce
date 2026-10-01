<?php
// onde o cliente ve o grid dos oculos rpzd

$produtos = [

    1 => [
        'id'             => 1,
        'nome'           => 'Óculos Comum',
        'categoria'      => 'comum',
        'personalizavel' => false,
        'preco'          => 9.00,
        'estoque'        => 0,
        'imagens'        => [

            '/../assets/images/oculosComum.png',
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

// Categoria vinda da URL (?categoria=comum ou ?categoria=personalizado).
// Sem parâmetro = mostra todos os produtos.
$categoria = isset($_GET['categoria']) ? strtolower(trim($_GET['categoria'])) : '';

$produtosFiltrados = array_filter($produtos, function ($produto) use ($categoria) {
    return $categoria === '' || $produto['categoria'] === $categoria;
});

$tituloPagina = ' ';
if ($categoria === 'comum') {
    $tituloPagina = 'Óculos Comum';
} elseif ($categoria === 'personalizado') { 
    $tituloPagina = 'Óculos Personalizado';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description" content="E-commerce - Página de produtos">

    <title><?php echo htmlspecialchars($tituloPagina); ?> | Polaris Óculos</title>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/produtos.css">
</head>

<body>
<?php
require_once __DIR__ . "/../components/header.php";
require_once __DIR__ . "/../components/sidebar.php";
?>

    <nav class="breadcrumb">
        <a href="../index.php">Início</a> ›
        <span aria-current="true"><?php echo htmlspecialchars($tituloPagina); ?></span>
    </nav>

    <main>
        <section class="products">
            <div class="container">

                <h1><?php echo htmlspecialchars($tituloPagina); ?></h1>

                <?php if (empty($produtosFiltrados)): ?>

                    <p>Nenhum produto encontrado nessa categoria.</p>

                <?php else: ?>

                    <div class="product-grid">
                        <?php foreach ($produtosFiltrados as $produtoItem): ?>
                            <?php
                            // product-card.php espera as chaves id / image / name / price
                            $product = [
                                'id'    => $produtoItem['id'],
                                'image' => $produtoItem['imagens'][0],
                                'name'  => $produtoItem['nome'],
                                'price' => $produtoItem['preco'],
                            ];
                            require __DIR__ . "/../components/product-card.php";
                            ?>
                        <?php endforeach; ?>
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