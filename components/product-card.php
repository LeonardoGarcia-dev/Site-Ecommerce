<?php
// Card do grid da página de produtos.
// Espera: $product['id'], ['image'], ['name'], ['price'] e (opcional) ['images'] com todas as fotos.
$galeria = (!empty($product['images']) && is_array($product['images']))
    ? array_values($product['images'])
    : [$product['image']];
$temGaleria = count($galeria) > 1;
?>
<article class="product-card">

    <a href="/pages/produto.php?id=<?php echo (int) $product['id']; ?>" class="product-card-link">

        <div class="product-image">
            <img
                src="<?php echo htmlspecialchars($galeria[0]); ?>"
                data-original="<?php echo htmlspecialchars($galeria[0]); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>"
            >

            <?php if ($temGaleria): ?>
                <!-- Miniaturas: aparecem no hover, igual à Nike. Passar o mouse numa miniatura troca a foto. -->
                <ul class="product-thumbs" aria-label="Outras fotos de <?php echo htmlspecialchars($product['name']); ?>">
                    <?php foreach ($galeria as $i => $foto): ?>
                        <li>
                            <span
                                role="button"
                                tabindex="0"
                                class="product-thumb<?php echo $i === 0 ? ' is-active' : ''; ?>"
                                data-src="<?php echo htmlspecialchars($foto); ?>"
                                aria-label="Ver foto <?php echo $i + 1; ?>"
                            >
                                <img src="<?php echo htmlspecialchars($foto); ?>" alt="" loading="lazy">
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="product-info">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="product-price">R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></p>
        </div>

    </a>

</article>
