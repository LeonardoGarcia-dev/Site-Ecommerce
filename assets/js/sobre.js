// Página Sobre: luz suave que desliza até o mouse (movimento fluido, com inércia).
document.addEventListener("DOMContentLoaded", function () {
    if (window.matchMedia("(hover: none), (prefers-reduced-motion: reduce)").matches) return;

    const glow = document.querySelector(".cursor-glow");
    const cards = document.querySelectorAll(".sobre-historia, .sobre-diferencial, .sobre-identidade");
    if (!glow) return;

    // alvo (mouse) e posição atual da luz; a luz persegue o alvo aos poucos
    let tx = window.innerWidth / 2, ty = window.innerHeight / 2;
    let x = tx, y = ty;
    let running = false;

    // posição suavizada do reflexo dentro do card
    const inner = new Map();
    let activeCard = null;

    const EASE_GLOW = 0.075;  // menor = mais lento/fluido
    const EASE_CARD = 0.12;

    function frame() {
        x += (tx - x) * EASE_GLOW;
        y += (ty - y) * EASE_GLOW;
        glow.style.transform = "translate3d(" + x.toFixed(1) + "px," + y.toFixed(1) + "px,0)";

        let moving = Math.abs(tx - x) > 0.3 || Math.abs(ty - y) > 0.3;

        if (activeCard) {
            const st = inner.get(activeCard);
            st.x += (st.tx - st.x) * EASE_CARD;
            st.y += (st.ty - st.y) * EASE_CARD;
            activeCard.style.setProperty("--mx", st.x.toFixed(1) + "px");
            activeCard.style.setProperty("--my", st.y.toFixed(1) + "px");
            if (Math.abs(st.tx - st.x) > 0.3 || Math.abs(st.ty - st.y) > 0.3) moving = true;
        }

        if (moving) {
            requestAnimationFrame(frame);
        } else {
            running = false;
        }
    }

    function start() {
        if (!running) {
            running = true;
            requestAnimationFrame(frame);
        }
    }

    let first = true;
    document.addEventListener("mousemove", function (e) {
        tx = e.clientX;
        ty = e.clientY;
        if (first) { x = tx; y = ty; first = false; } // aparece já no lugar certo
        glow.classList.add("is-visible");
        start();
    });

    document.documentElement.addEventListener("mouseleave", function () {
        glow.classList.remove("is-visible");
    });

    cards.forEach(function (card) {
        inner.set(card, { x: 0, y: 0, tx: 0, ty: 0 });

        card.addEventListener("mouseenter", function (e) {
            const r = card.getBoundingClientRect();
            const st = inner.get(card);
            st.x = st.tx = e.clientX - r.left;
            st.y = st.ty = e.clientY - r.top;
            activeCard = card;
            start();
        });

        card.addEventListener("mousemove", function (e) {
            const r = card.getBoundingClientRect();
            const st = inner.get(card);
            st.tx = e.clientX - r.left;
            st.ty = e.clientY - r.top;
            start();
        });
    });
});