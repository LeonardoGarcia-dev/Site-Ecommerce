<?php
require_once __DIR__ . "/../config/util.php";
$conexao = conecta();
// Onde o cliente ve o grid dos oculos rpzd
$produtos = [
    1 => [
        'id'             => 1,
        'nome'           => 'Óculos Comum',
        'categoria'      => 'comum',
        'personalizavel' => false,
        'preco'          => 9.00, // Será sobrescrito pelo BD
        'estoque'        => 0,    // Será sobrescrito pelo BD
        'imagens'        => [
            '../assets/images/oculosComum.png',
            '../assets/images/oculosComumDireita.png',
            '../assets/images/oculosComumEsquerda.png',
            '../assets/images/oculosComumModelo.png',
            '../assets/images/oculosComumModela.png',
        ],
        'descricao'      => 'Estilo clássico e versatilidade essencial: o óculos preto perfeito para qualquer ocasião',
        'vendedor'       => 'Polaris Óculos',
    ],
    2 => [
        'id'             => 2,
        'nome'           => 'Óculos Personalizado',
        'categoria'      => 'personalizado',
        'personalizavel' => true,
        'preco'          => 12.00, // Será sobrescrito pelo BD
        'estoque'        => 0,     // Será sobrescrito pelo BD
        'imagens'        => [
            '../assets/images/oculosPersonalizadoFrente.png',
            '../assets/images/oculosPersonalizadoEsquerda.png',
            '../assets/images/oculosPersonalizadoDireita.png',
            '../assets/images/oculosPersonalizadoModelo.png',
            '../assets/images/oculosPersonalizadoModela.png',
        ],
        'descricao'      => 'Sua personalidade em destaque: o óculos que transforma a sua mensagem no seu maior estilo.',
        'vendedor'       => 'Polaris Óculos',
    ],
];
// --- PUXANDO PREÇOS E QUANTIDADES DO BANCO DE DADOS ---
// Buscamos todos os produtos do banco para cruzar com o nosso array
$sqlProdutos = "SELECT id_produto, valor_unitario, quantidade FROM produto";
$stmt = $conexao->query($sqlProdutos);
$dadosBanco = [];
while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dadosBanco[$linha['id_produto']] = $linha;
}
// Atualizando o array estático com as informações reais do banco
foreach ($produtos as $key => $prod) {
    $idProd = $prod['id'];
    if (isset($dadosBanco[$idProd])) {
        $produtos[$key]['preco'] = $dadosBanco[$idProd]['valor_unitario'];
        $produtos[$key]['estoque'] = $dadosBanco[$idProd]['quantidade'];
    } else {
        $produtos[$key]['estoque'] = 0; // Se não achar no banco, zera por segurança
    }
}
// Categoria vinda da URL (?categoria=comum ou ?categoria=personalizado).
// Sem parâmetro = mostra todos os produtos.
$categoria = isset($_GET['categoria']) ? strtolower(trim($_GET['categoria'])) : '';
$produtosFiltrados = array_filter($produtos, function ($produto) use ($categoria) {
    return $categoria === '' || $produto['categoria'] === $categoria;
});
$tituloPagina = 'Todos os óculos';
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
    
    <style>
        /* Estilos básicos inline para os botões do card de produto (podem ser ajustados no seu CSS) */
        .product-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        .btn-detalhes {
            background-color: transparent;
            border: 1px solid #333;
            color: #333;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            flex: 1;
        }
        .btn-detalhes:hover {
            background-color: #f0f0f0;
        }
        .btn-reservar {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            flex: 1;
        }
        .btn-reservar:hover {
            background-color: #333;
        }
        .btn-disabled {
            background-color: #ccc;
            border-color: #ccc;
            cursor: not-allowed;
            color: #666;
            padding: 8px 12px;
            border-radius: 4px;
            text-align: center;
            flex: 1;
            border: none;
        }
        .estoque-aviso {
            font-size: 0.85em;
            color: #d9534f;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
    </style>
</head>
<body>
<?php
require_once __DIR__ . "/../components/header.php";
require_once __DIR__ . "/../components/sidebar.php";
?>
    <main>
        <section class="products">
            <div class="container">
                <h1><?php echo htmlspecialchars($tituloPagina); ?></h1>
                <?php if (empty($produtosFiltrados)): ?>
                    <p>Nenhum produto encontrado nessa categoria.</p>
                <?php else: ?>
                    <div class="product-grid">
                        <?php foreach ($produtosFiltrados as $produtoItem): ?>
                            
                            <!-- Início do Card de Produto Modificado -->
                            <div class="product-card">
                                <a href="produto.php?id=<?php echo $produtoItem['id']; ?>" class="product-card-link">
                                    <div class="product-image">
                                        <img src="<?php echo htmlspecialchars($produtoItem['imagens'][0]); ?>" alt="<?php echo htmlspecialchars($produtoItem['nome']); ?>">
                                    </div>
                                </a>
                                
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($produtoItem['nome']); ?></h3>
                                    <p class="product-price">R$ <?php echo number_format($produtoItem['preco'], 2, ',', '.'); ?></p>
                                    
                                    <?php if ($produtoItem['estoque'] <= 0): ?>
                                        <span class="estoque-aviso">Produto Fora de Estoque</span>
                                    <?php endif; ?>
                                    <div class="product-actions">
                                        <!-- Botão Detalhes redirecionando para a página do produto -->
                                        <a href="produto.php?id=<?php echo $produtoItem['id']; ?>" class="btn-detalhes">Detalhes</a>
                                        
                                        <!-- Formulário e botão de Reservar -->
                                        <?php if ($produtoItem['estoque'] > 0): ?>
                                            <form action="carrinho.php" method="post" style="display: flex; flex: 1; margin: 0;">
                                                <input type="hidden" name="operacao" value="incluir">
                                                <input type="hidden" name="id_produto" value="<?php echo $produtoItem['id']; ?>">
                                                <input type="hidden" name="quantidade" value="1">
                                                <button type="submit" class="btn-reservar" style="width: 100%;">Realizar Reserva</button>
                                            </form>
                                        <?php else: ?>
                                            <!-- Botão desativado se não tiver estoque -->
                                            <button type="button" class="btn-disabled" disabled>Indisponível</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Fim do Card -->
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
    <script src="../assets/js/produtos.js"></script>
</body>
</html>