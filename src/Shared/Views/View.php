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
    <link rel="shortcut icon" href="<?= $baseUrl ?>/src/Shared/Views/favicon.ico">
    <link rel="stylesheet" href="<?= $baseUrl ?>/src/Shared/Views/jquery-ui.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/src/Shared/Views/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/src/Shared/Views/bootstrap.min.css">
    <script src="<?= $baseUrl ?>/src/Shared/Views/jquery-3.4.1.min.js"></script>
    <script src="<?= $baseUrl ?>/src/Shared/Views/jquery-ui.min.js"></script>
    <script src="<?= $baseUrl ?>/src/Shared/Views/knockout-min.js"></script>
    <script src="<?= $baseUrl ?>/src/Shared/Views/knockout.mapping.js"></script>
    <script src="<?= $baseUrl ?>/src/Shared/Views/knockout-sortable.min.js"></script>
    <?php
    $touchScript = "<script src=\"$baseUrl/src/Shared/Views/jquery.ui.touch-punch.min.js\"></script>";
    if ($_GET['touch'] == 'true') {
        echo $touchScript;
    }
    ?>
    <script src="<?= $baseUrl ?>/src/Shared/Views/remarkable.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-light bg-light mb-3">
    <a class="navbar-brand" href="<?= $baseUrl ?>/src/CatFeeding/Views">
        <img src="<?= $baseUrl ?>/src/Shared/Views/icon.png" alt="Asystentka domowa"/>
    </a>
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
                <a class="nav-link" href="<?= $baseUrl ?>/src/Coffee/Views">Kawa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/src/Flat/Views">Mieszkanie</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/src/CarMaintenance/Views">Golf</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/src/ToDo/Views?name=lukasz">Zadania Łukasza</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $baseUrl ?>/src/ToDo/Views?name=ilona">Zadania Ilony</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">