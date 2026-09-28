<?php
/**
 * URL base do projeto no servidor.
 *
 * O site é acessado em:
 *   http://eq.projetoscti.com.br/loja4n/
 *
 * Ou seja, o subdomínio "eq." já tem como raiz (DocumentRoot) a pasta
 * "equipes" (/var/www/equipes), então na URL pública só aparece "/loja4n".
 *
 * Como o site NÃO fica na raiz do domínio, qualquer link que comece com "/"
 * (ex: href="/index.php") quebra, pois o navegador procura em
 * http://eq.projetoscti.com.br/index.php em vez de .../loja4n/index.php.
 *
 * Por isso, em vez de caminhos fixos "/algo", usamos BASE_URL . "/algo"
 * nos componentes (navbar, sidebar, footer, product-card) que são
 * incluídos tanto pela raiz (index.php) quanto por pastas internas
 * (pages/, admin/), onde um caminho relativo simples não serviria para os dois casos.
 *
 * Se um dia o projeto for movido para outra pasta/domínio, basta ajustar
 * o valor abaixo.
 */
if (!defined('BASE_URL')) {
    define('BASE_URL', '/loja4n');
}
