<?php
include '../../Shared/UseCases/UseCase.php';

$doseId = intval($_REQUEST['Id']);

if (notValidId($doseId)) {
    showFinalWarning('Nie wybrano dawki.');
    return;
}

$update = pdo()->prepare('update medicine_dose set visible = 1 where id = ?');
$updated = $update->execute([$doseId]);

if ($updated) {
    showInfo('Dawka pokazana.');
} else {
    showError('Nie udało się pokazać dawki!');
    showStatementError($update);
}


include '../../Shared/Views/Footer.php';