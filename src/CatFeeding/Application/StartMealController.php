<?php

include __DIR__ . '/../../Configuration.php';

$catId = 1;
$foodId = intval($_REQUEST['FoodId']);
$weight = intval($_REQUEST['Weight']);

if ($foodId == 0) {
    echo 'Nie wybrano pokarmu. Spróbuj jeszcze raz.';
    return;
}

$saveMeal = $pdo->prepare('INSERT INTO meal (cat_id, food_id, start, start_weight) values (?, ?, ?, ?)');
$mealSaved = $saveMeal->execute([$catId, $foodId, date('Y-m-d H:i:s'), $weight]);

if ($mealSaved) {
    echo 'Posiłek rozpoczęty. ';
} else {
    echo 'Nie udało się rozpocząć posiłku! ';
}