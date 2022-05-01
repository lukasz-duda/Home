<?php
include '../../Shared/UseCases/UseCase.php';

$catId = intval($_REQUEST['CatId']);
$weight = floatval($_REQUEST['Weight']);

if (notValidId($catId)) {
    showFinalWarning('Nie wybrano kota.');
}

if (notValidValue($weight)) {
    showFinalWarning('Nie podano wagi.');
}

$save = pdo()->prepare('INSERT INTO weight (cat_id, date, weight) VALUES (?, ?, ?)');
$saved = $save->execute([$catId, today(), $weight]);

if ($saved) {
    showInfo('Udało się zważyć kota.');
} else {
    showError('Nie udało się dodać wagi kota!');
    showStatementError($save);
}

include '../../Shared/Views/Footer.php';