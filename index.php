<?php
include __DIR__ . '/src/Shared/Views/View.php';

$catId = 1;
$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);
$carId = 1;
$car = get('SELECT name FROM cars WHERE id = ?', [$carId]);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Asystentka domowa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="container">
<h1><a href="src/CatFeeding/Views"><?= $cat['name'] ?></a></h1>
<h1><a href="src/CarMaintenance/Views"><?= $car['name'] ?></a></h1>
</body>
</html>