<?php
include '../../Shared/Views/View.php';

$mealId = intval($_REQUEST['MealId']);
$weight = intval($_REQUEST['Weight']);

$saveMeal = $pdo->prepare('UPDATE meal SET end = ?, end_weight = ? where id = ?');
$mealSaved = $saveMeal->execute([date('Y-m-d H:i:s'), $weight, $mealId]);

if ($mealSaved) {
    showMessage('Posiłek zakończony.');
} else {
    showMessage('Nie udało się zakończyć posiłku!');
}

include '../../Shared/Views/Footer.php';