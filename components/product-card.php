<article class="product-card">

    <a
        href="pages/produto.php?id=<?php echo $product['id']; ?>"
        class="product-card-link"
    >

        <div class="product-image">

            <img
                src="<?php echo htmlspecialchars($product['image']); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>"
            >

        </div>


        <div class="product-info">

            <h3>
                <?php echo htmlspecialchars($product['name']); ?>
            </h3>


            <p class="product-price">

                R$

                <?php
                echo number_format(
                    $product['price'],
                    2,
                    ',',
                    '.'
                );
                ?>

            </p>

        </div>

    </a>

</article>