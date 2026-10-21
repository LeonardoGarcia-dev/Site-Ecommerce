document.addEventListener("DOMContentLoaded", () => {
    // ========================================
    // ANIMAÇÕES DE ENTRADA (REVEAL)
    // ========================================

    const elementosReveal = document.querySelectorAll(".reveal");

    // Fallback para navegadores sem suporte ao IntersectionObserver
    if (!("IntersectionObserver" in window)) {
        elementosReveal.forEach((elemento) => {
            elemento.classList.add("visible");
        });
    } else {
        const observer = new IntersectionObserver(
            (entradas, observador) => {
                entradas.forEach((entrada) => {
                    if (entrada.isIntersecting) {
                        entrada.target.classList.add("visible");
                        observador.unobserve(entrada.target);
                    }
                });
            },
            {
                threshold: 0.12,
                rootMargin: "0px 0px -30px 0px"
            }
        );

        elementosReveal.forEach((elemento, index) => {
            // Pequeno atraso para criar um efeito sequencial
            elemento.style.transitionDelay = `${(index % 4) * 100}ms`;
            observer.observe(elemento);
        });
    }

    // ========================================
    // CARROSSEL AUTOMÁTICO DO BANNER
    // ========================================

    const track = document.querySelector(".carrossel-track");

    if (track) {
        const slides = Array.from(track.querySelectorAll(".slide"));
        let slideAtual = 0;
        let intervalo = null;

        const movimentoReduzido = window.matchMedia(
            "(prefers-reduced-motion: reduce)"
        ).matches;

        // Configura o carrossel
        track.style.transition = movimentoReduzido
            ? "none"
            : "transform 0.7s ease-in-out";

        function mostrarSlide(indice) {
            slideAtual = indice;
            track.style.transform = `translateX(-${slideAtual * 100}%)`;
        }

        // Inicia somente se houver mais de uma imagem
        if (slides.length > 1) {
            mostrarSlide(0);

            if (!movimentoReduzido) {
                intervalo = window.setInterval(() => {
                    const proximoSlide = (slideAtual + 1) % slides.length;
                    mostrarSlide(proximoSlide);
                }, 5000);
            }
        }

        // Evita manter o intervalo quando a página está oculta
        document.addEventListener("visibilitychange", () => {
            if (document.hidden && intervalo !== null) {
                clearInterval(intervalo);
                intervalo = null;
            } else if (
                !document.hidden &&
                !movimentoReduzido &&
                slides.length > 1 &&
                intervalo === null
            ) {
                intervalo = window.setInterval(() => {
                    mostrarSlide((slideAtual + 1) % slides.length);
                }, 5000);
            }
        });
    }
});
