<?php
include "../../Shared/Views/View.php";
$catId = 1;
$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);
$catName = $cat['name'];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $catName ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="container">
<h1><?= $catName ?></h1>
<h2>Dodaj pokarm</h2>
<form action="../Application/AddFoodController.php" method="post">
    <div class="form-group">
        <label for="FoodName">Nazwa</label>
        <input class="form-control" id="FoodName" name="FoodName"/>
    </div>
    <div class="form-group">
        <label for="FoodDescription">Opis</label>
        <textarea id="FoodDescription" name="FoodDescription" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label for="Weight">Zapotrzebowanie dzienne [g]</label>
        <input id="Weight" name="Weight" class="form-control" type="number" step="1" min="0" max="1000"/>
    </div>
    <button type="submit" class="btn btn-primary">Zapisz</button>
</form>
<h2>Popraw pokarm</h2>
<h2>Podsumowanie dnia</h2>
<h2>Rozpocznij posiłek</h2>
<h2>Zakończ posiłek</h2>
<h2>Kupa</h2>
<h2>Siku</h2>
<h2>Obserwuj</h2>
</body>
</html>