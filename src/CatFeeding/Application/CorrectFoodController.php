<?php

include __DIR__ . '/../../Configuration.php';

$catId = 1;
$foodId = intval($_REQUEST['FoodId']);
$name = $_REQUEST['FoodName'];
$description = $_REQUEST['FoodDescription'];
$weight = intval($_REQUEST['Weight']);
$pdo = newPdo();

$saveFood = $pdo->prepare('UPDATE food SET name = ?, description = ? where id = ?');
$foodSaved = $saveFood->execute([$name, $description, $foodId]);

if ($foodSaved) {
    echo 'Pokarm poprawiony. ';
    $addDailyDemand = $pdo->prepare('UPDATE daily_demand SET weight = ? where cat_id = ? and food_id = ?');
    $dailyDemandUpdated = $addDailyDemand->execute([$weight, $catId, $foodId]);
    if ($dailyDemandUpdated) {
        echo 'Dzienne zapotrzebowanie poprawione. ';
    } else {
        echo 'Nie udało się poprawione dziennego zapotrzebowania. ';
    }
} else {
    echo 'Nie udało się poprawić pokarmu! ';
}