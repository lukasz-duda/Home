<?php

include '../../Shared/Views/View.php';

$catId = intval($_REQUEST['CatId']);
$name = $_REQUEST['FoodName'];
$description = $_REQUEST['FoodDescription'];
$weight = intval($_REQUEST['Weight']);

$saveFood = pdo()->prepare('INSERT INTO food (name, description, visible) VALUES (?, ?, ?)');
$foodSaved = $saveFood->execute([$name, $description, 1]);
$foodId = pdo()->lastInsertId();

if ($foodSaved) {
    showInfo('Pokarm dodany.');
    $addDailyDemand = pdo()->prepare('INSERT INTO daily_demand (cat_id, food_id, weight, timestamp) VALUES (?, ?, ?, ?)');
    $dailyDemandUpdated = $addDailyDemand->execute([$catId, $foodId, $weight, date('Y-m-d H:i:s')]);
    if ($dailyDemandUpdated) {
        showInfo('Dzienne zapotrzebowanie dodane.');
    } else {
        showError('Nie udało się dodać dziennego zapotrzebowania.');
        showStatementError($addDailyDemand);
    }
} else {
    showError('Nie udało się zapisać pokarmu!');
    showStatementError($saveFood);
}

include '../../Shared/Views/Footer.php';