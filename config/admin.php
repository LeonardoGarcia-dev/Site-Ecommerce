<?php
require_once __DIR__ . '/util.php';

function adminHeader(string $title): void
{
    ?><!doctype html>
    <html lang="pt-BR"><head>
        <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?= e($title) ?> | Polaris</title>
        <link rel="stylesheet" href="/assets/css/global.css">
        <link rel="stylesheet" href="/assets/css/header.css">
        <link rel="stylesheet" href="/assets/css/footer.css">
        <link rel="stylesheet" href="/assets/css/admin.css">
    </head><body><?php require_once __DIR__ . '/../components/header.php'; ?>
}

function adminFooter(): void
{
    <?php require_once __DIR__ . '/../components/footer.php'; ?></body></html><?php
}
