// Página de produtos: passar o mouse numa miniatura troca a foto do card (estilo Nike).
// Ao tirar o mouse do card, volta para a foto original.
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".product-card").forEach(function (card) {
        const main = card.querySelector(".product-image > img");
        const thumbs = card.querySelectorAll(".product-thumb");
        if (!main || !thumbs.length) return;

        function show(thumb) {
            main.src = thumb.dataset.src;
            thumbs.forEach(function (t) { t.classList.toggle("is-active", t === thumb); });
        }

        thumbs.forEach(function (thumb) {
            thumb.addEventListener("mouseenter", function () { show(thumb); });
            thumb.addEventListener("focus", function () { show(thumb); });
            // no celular o toque na miniatura só troca a foto (não abre o produto)
            thumb.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                show(thumb);
            });
        });

        card.addEventListener("mouseleave", function () {
            show(thumbs[0]);
        });
    });
});
