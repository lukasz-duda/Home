<?php
include '../../Shared/Views/View.php';

$catId = intval($_REQUEST['CatId']);
$foodId = intval($_REQUEST['FoodId']);
$name = $_REQUEST['FoodName'];
$description = $_REQUEST['FoodDescription'];
$weight = intval($_REQUEST['Weight']);
$visible = intval($_REQUEST['Visible']);

$saveFood = $pdo->prepare('UPDATE food SET name = ?, description = ?, visible = ? where id = ?');
$foodSaved = $saveFood->execute([$name, $description, $visible, $foodId]);

if ($foodSaved) {
    showInfo('Pokarm poprawiony.');
    $lastDemand = get('select d.weight
from daily_demand d
where d.cat_id = ?
  and d.food_id = ?
order by d.timestamp desc
limit 1', [$catId, $foodId]);
    if ($weight == $lastDemand['weight']) {
        showInfo('Dzienne zapotrzebowanie niezmienione.');
        return;
    }
    $addDailyDemand = $pdo->prepare('INSERT INTO daily_demand (cat_id, food_id, weight, timestamp) VALUES (?, ?, ?, ?)');
    $dailyDemandUpdated = $addDailyDemand->execute([$catId, $foodId, $weight, date('Y-m-d H:i:s')]);
    if ($dailyDemandUpdated) {
        showInfo('Dzienne zapotrzebowanie dodane.');
    } else {
        showError('Nie udało się dodanie dziennego zapotrzebowania.');
        showStatementError($addDailyDemand);
    }
} else {
    showError('Nie udało się poprawić pokarmu!');
    showStatementError($saveFood);
}

include '../../Shared/Views/Footer.php';