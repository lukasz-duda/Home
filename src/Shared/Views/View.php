<?php
include __DIR__ . '/../../Configuration.php';
include __DIR__ . '/ViewUtils.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dom</title>
    <meta name="theme-color" content="#4285f4">
    <link rel="shortcut icon" sizes="192x192" href="<?= baseUrl() ?>/src/Shared/Views/Icon/home_192.png">
    <link rel="icon" href="<?= baseUrl() ?>/favicon.ico" type="image/x-icon">
    <link rel="manifest" href="<?= baseUrl() ?>/manifest.json" crossorigin="use-credentials">
    <link rel="stylesheet" href="<?= baseUrl() ?>/src/Shared/Views/jquery-ui.min.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>/src/Shared/Views/bootstrap.min.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>/src/Shared/Views/Chart.min.css">
    <link href="<?= baseUrl() ?>/src/Shared/Views/material-icons.css" rel="stylesheet">
    <link href="<?= baseUrl() ?>/src/Shared/Views/theme.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= baseUrl() ?>/src/ToDo/Views/eisenhower-matrix.css">
    <script src="<?= baseUrl() ?>/src/Shared/Views/jquery-3.4.1.min.js"></script>
    <script src="<?= baseUrl() ?>/src/Shared/Views/jquery-ui.min.js"></script>
    <script src="<?= baseUrl() ?>/src/Shared/Views/knockout-min.js"></script>
    <script src="<?= baseUrl() ?>/src/Shared/Views/knockout.mapping.js"></script>
    <script src="<?= baseUrl() ?>/src/Shared/Views/knockout-sortable.min.js"></script>
    <script src="<?= baseUrl() ?>/src/Shared/Views/jquery.ui.touch-punch.min.js"></script>
    <script src="<?= baseUrl() ?>/src/Shared/Views/remarkable.min.js"></script>
    <script src="<?= baseUrl() ?>/src/Shared/Views/Chart.bundle.min.js"></script>
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
                <a class="nav-link" title="Wstecz"
                   href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : baseUrl() ?>"><i
                            class="material-icons-outlined">arrow_back</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Dom" href="<?= baseUrl() ?>/index.php"><i
                            class="material-icons-outlined">home</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Szyszka" href="<?= baseUrl() ?>/src/CatFeeding/Views/cat.php?Id=1"><i
                            class="material-icons-outlined cat-1">pets</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Mgiełka" href="<?= baseUrl() ?>/src/CatFeeding/Views/cat.php?Id=2"><i
                            class="material-icons-outlined cat-2">pets</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Zakupy" href="<?= baseUrl() ?>/src/Shopping/Views/expenses.php"><i
                            class="material-icons-outlined">local_grocery_store</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Golf" href="<?= baseUrl() ?>/src/CarMaintenance/Views/car.php?Id=1"><i
                            class="material-icons-outlined">directions_car</i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" title="Zadania Łukasz"
                   href="<?= baseUrl() ?>/src/ToDo/Views/edit.php?name=Łukasz"><i
                            class="material-icons-outlined">playlist_add_check</i></a>
            </li>
        </ul>
    </div>
</nav>
<script>
    $('.nav-link').each(function (index, menuItem) {
        if (menuItem.href === location.href) {
            menuItem.classList.add('active');
        }
    });
</script>
<div class="container">
