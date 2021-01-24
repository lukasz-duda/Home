<?php
include '../../Shared/UseCases/UseCase.php';

$doseId = intval($_REQUEST['Id']);

if (notValidId($doseId)) {
    showFinalWarning('Nie wybrano dawki.');
    return;
}

$update = pdo()->prepare('update medicine_dose set visible = 0 where id = ?');
$updated = $update->execute([$doseId]);

if ($updated) {
    showInfo('Dawka ukryta.');
} else {
    showError('Nie udało się ukryć dawki!');
    showStatementError($update);
}


include '../../Shared/Views/Footer.php';