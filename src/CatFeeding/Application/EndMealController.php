<?php

include __DIR__ . '/../../Configuration.php';

$mealId = intval($_REQUEST['MealId']);
$weight = intval($_REQUEST['Weight']);

$saveMeal = $pdo->prepare('UPDATE meal SET end = ?, end_weight = ? where id = ?');
$mealSaved = $saveMeal->execute([date('Y-m-d H:i:s'), $weight, $mealId]);

if ($mealSaved) {
    echo 'Posiłek zakończony. ';
} else {
    echo 'Nie udało się zakończyć posiłku! ';
}