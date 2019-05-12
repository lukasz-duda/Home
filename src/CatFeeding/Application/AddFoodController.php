<?php

include __DIR__ . '/../../Configuration.php';

$catId = 1;
$name = $_REQUEST['FoodName'];
$description = $_REQUEST['FoodDescription'];
$weight = intval($_REQUEST['Weight']);
$pdo = newPdo();

$saveFood = $pdo->prepare('INSERT INTO food (name, description) VALUES (?, ?)');
$foodSaved = $saveFood->execute([$name, $description]);
$foodId = $pdo->lastInsertId();

if ($foodSaved) {
    echo 'Pokarm dodany. ';
    $addDailyDemand = $pdo->prepare('INSERT INTO daily_demand (cat_id, food_id, weight) VALUES (?, ?, ?)');
    $dailyDemandUpdated = $addDailyDemand->execute([$catId, $foodId, $weight]);
    if ($dailyDemandUpdated) {
        echo 'Dzienne zapotrzebowanie dodane. ';
    } else {
        echo 'Nie udało się dodać dziennego zapotrzebowania. ';
    }
} else {
    echo 'Nie udało się zapisać pokarmu! ';
}