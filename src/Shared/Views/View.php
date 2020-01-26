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
    <link href="<?= $baseUrl ?>/src/Shared/Views/material-icons.css" rel="stylesheet">
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
<nav class="navbar navbar-expand navbar-light bg-light mb-3">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" title="Szyszka" href="<?= $baseUrl ?>/src/CatFeeding/Views"><i
                            class="material-icons-outlined">pets</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Zakupy" href="<?= $baseUrl ?>/src/Shopping/Views"><i
                            class="material-icons-outlined">local_grocery_store</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Kawa" href="<?= $baseUrl ?>/src/Coffee/Views"><i
                            class="material-icons-outlined">local_cafe</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Mieszkanie" href="<?= $baseUrl ?>/src/Flat/Views"><i
                            class="material-icons-outlined">home</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Golf" href="<?= $baseUrl ?>/src/CarMaintenance/Views"><i
                            class="material-icons-outlined">directions_car</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Zadania Łukasz" href="<?= $baseUrl ?>/src/ToDo/Views?name=lukasz"><i
                            class="material-icons-outlined">playlist_add</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Zadania Ilona" href="<?= $baseUrl ?>/src/ToDo/Views?name=ilona"><i
                            class="material-icons-outlined">playlist_add_check</i></a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">