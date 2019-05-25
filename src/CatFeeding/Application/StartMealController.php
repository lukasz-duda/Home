<?php
include '../../Shared/Views/View.php';

$catId = 1;
$foodId = intval($_REQUEST['FoodId']);
$weight = intval($_REQUEST['Weight']);

if ($foodId == 0) {
    showMessage('Nie wybrano pokarmu. Spróbuj jeszcze raz.');
    return;
}

$saveMeal = $pdo->prepare('INSERT INTO meal (cat_id, food_id, start, start_weight) values (?, ?, ?, ?)');
$mealSaved = $saveMeal->execute([$catId, $foodId, date('Y-m-d H:i:s'), $weight]);

if ($mealSaved) {
    showMessage('Posiłek rozpoczęty.');
} else {
    showMessage('Nie udało się rozpocząć posiłku!');
}

include '../../Shared/Views/Footer.php';