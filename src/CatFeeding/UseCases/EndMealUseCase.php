<?php
include '../../Shared/UseCases/UseCase.php';

$mealId = intval($_REQUEST['MealId']);
$endWeight = intval($_REQUEST['Weight']);

if (notValidId($mealId)) {
    showFinalWarning('Niepoprawny identyfikator posiłku.');
}

$meal = get('SELECT m.start_weight FROM meal m WHERE id = ?', [$mealId]);
$startWeight = $meal['start_weight'];

if ($endWeight > $startWeight) {
    showFinalWarning('Waga nie może być większa niż na początku posiłku.');
}

$saveMeal = pdo()->prepare('UPDATE meal SET end = ?, end_weight = ? where id = ?');
$mealSaved = $saveMeal->execute([date('Y-m-d H:i:s'), $endWeight, $mealId]);

if ($mealSaved) {
    showInfo('Posiłek zakończony.');
} else {
    showError('Nie udało się zakończyć posiłku!');
    showStatementError($saveMeal);
}

include '../../Shared/Views/Footer.php';