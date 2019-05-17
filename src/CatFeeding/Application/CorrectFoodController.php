<?php

include __DIR__ . '/../../Configuration.php';

$catId = 1;
$foodId = intval($_REQUEST['FoodId']);
$name = $_REQUEST['FoodName'];
$description = $_REQUEST['FoodDescription'];
$weight = intval($_REQUEST['Weight']);

$saveFood = $pdo->prepare('UPDATE food SET name = ?, description = ? where id = ?');
$foodSaved = $saveFood->execute([$name, $description, $foodId]);

if ($foodSaved) {
    echo 'Pokarm poprawiony. ';
    $lastDemand = get('select d.weight
from daily_demand d
where d.cat_id = ?
  and d.food_id = ?
order by d.timestamp desc
limit 1', [$catId, $foodId]);
    if ($weight == $lastDemand['weight']) {
        echo 'Dzienne zapotrzebowanie niezmienione.';
        return;
    }
    $addDailyDemand = $pdo->prepare('INSERT INTO daily_demand (cat_id, food_id, weight, timestamp) VALUES (?, ?, ?, ?)');
    $dailyDemandUpdated = $addDailyDemand->execute([$catId, $foodId, $weight, date('Y-m-d H:i:s')]);
    if ($dailyDemandUpdated) {
        echo 'Dzienne zapotrzebowanie dodane. ';
    } else {
        echo 'Nie udało się dodanie dziennego zapotrzebowania. ';
    }
} else {
    echo 'Nie udało się poprawić pokarmu! ';
}