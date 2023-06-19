<?php
include '../../Shared/UseCases/UseCase.php';

$mealId = intval($_REQUEST['MealId']);
$endWeight = intval($_REQUEST['NewEndWeight']);

if (notValidId($mealId)) {
    showFinalWarning('Niepoprawny identyfikator posiłku.');
}

$meal = get('SELECT m.start_weight FROM meal m WHERE id = ?', [$mealId]);
$startWeight = $meal['start_weight'];

if ($endWeight > $startWeight) {
    showFinalWarning('Waga nie może być większa niż na początku posiłku.');
}

$saveMeal = pdo()->prepare('UPDATE meal SET end_weight = ? where id = ?');
$mealSaved = $saveMeal->execute([$endWeight, $mealId]);

if ($mealSaved) {
    showInfo('Posiłek poprawiony.');
} else {
    showError('Nie udało się poprawić posiłku!');
    showStatementError($saveMeal);
}

include '../../Shared/Views/Footer.php';
