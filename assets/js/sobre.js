// Página Sobre: luz suave que segue o mouse.
document.addEventListener("DOMContentLoaded", function () {
    if (window.matchMedia("(hover: none), (prefers-reduced-motion: reduce)").matches) return;

    const glow = document.querySelector(".cursor-glow");
    const cards = document.querySelectorAll(".sobre-historia, .sobre-diferencial, .sobre-identidade");
    if (!glow) return;

    let x = 0, y = 0, ticking = false;

    function update() {
        glow.style.transform = "translate(" + x + "px, " + y + "px)";
        ticking = false;
    }

    document.addEventListener("mousemove", function (e) {
        x = e.clientX;
        y = e.clientY;
        glow.classList.add("is-visible");
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
        }
    });

    document.addEventListener("mouseleave", function () {
        glow.classList.remove("is-visible");
    });

    // reflexo sutil dentro do card sob o mouse
    cards.forEach(function (card) {
        card.addEventListener("mousemove", function (e) {
            const r = card.getBoundingClientRect();
            card.style.setProperty("--mx", (e.clientX - r.left) + "px");
            card.style.setProperty("--my", (e.clientY - r.top) + "px");
        });
    });
});
