// Scripts da página inicial

document.addEventListener("DOMContentLoaded", function () {

    // Aparecer ao rolar: adiciona .visivel quando o elemento entra na tela
    const itens = document.querySelectorAll(".reveal");

    if (!("IntersectionObserver" in window)) {
        itens.forEach(function (el) { el.classList.add("visivel"); });
        return;
    }

    const observador = new IntersectionObserver(function (entradas) {
        entradas.forEach(function (entrada) {
            if (entrada.isIntersecting) {
                const el = entrada.target;
                el.classList.add("visivel");
                observador.unobserve(el);

                // depois que a entrada termina, devolve o elemento ao estilo normal
                // (assim o hover dos cards volta a funcionar sem atraso)
                setTimeout(function () {
                    el.classList.remove("reveal", "visivel");
                    el.style.transitionDelay = "";
                }, 1200);
            }
        });
    }, { threshold: 0.15 });

    itens.forEach(function (el, i) {
        // pequeno atraso escalonado entre irmãos (cards lado a lado)
        el.style.transitionDelay = (i % 4) * 0.08 + "s";
        observador.observe(el);
    });

    // Brilho que segue o mouse no botao ver produtos
        document.querySelectorAll(".btn-outline, .hero-actions .btn, .cta .btn").forEach(function (botao) {        botao.addEventListener("mousemove", function (e) {
            const area = botao.getBoundingClientRect();
            botao.style.setProperty("--x", (e.clientX - area.left) + "px");
            botao.style.setProperty("--y", (e.clientY - area.top) + "px");
        });
    });

});