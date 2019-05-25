<?php
include __DIR__ . '/../../Configuration.php';
include __DIR__ . '/ViewUtils.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Asystentka domowa</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/src/Shared/Views/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-light bg-light mb-3">
    <a class="navbar-brand" href="<?= $baseUrl ?>">Asystentka domowa</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/src/CatFeeding/Views">Szyszka</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/src/Shopping/Views">Zakupy</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/src/Flat/Views">Mieszkanie</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/src/CarMaintenance/Views">Golf</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">