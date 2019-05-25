<?php

include '../../Shared/Views/View.php';

$catId = 1;
$name = $_REQUEST['FoodName'];
$description = $_REQUEST['FoodDescription'];
$weight = intval($_REQUEST['Weight']);

$saveFood = $pdo->prepare('INSERT INTO food (name, description) VALUES (?, ?)');
$foodSaved = $saveFood->execute([$name, $description]);
$foodId = $pdo->lastInsertId();

if ($foodSaved) {
    showMessage('Pokarm dodany.');
    $addDailyDemand = $pdo->prepare('INSERT INTO daily_demand (cat_id, food_id, weight, timestamp) VALUES (?, ?, ?, ?)');
    $dailyDemandUpdated = $addDailyDemand->execute([$catId, $foodId, $weight, date('Y-m-d H:i:s')]);
    if ($dailyDemandUpdated) {
        showMessage('Dzienne zapotrzebowanie dodane.');
    } else {
        showMessage('Nie udało się dodać dziennego zapotrzebowania.');
    }
} else {
    showMessage('Nie udało się zapisać pokarmu!');
}

include '../../Shared/Views/Footer.php';