<?php
include '../../Shared/UseCases/UseCase.php';

$doseId = intval($_REQUEST['Id']);

if (notValidId($doseId)) {
    showFinalWarning('Nie wybrano dawki.');
    return;
}

$dose = get('SELECT d.cat_id, d.medicine_id, d.dose, d.unit FROM medicine_dose d WHERE d.id = ?', [$doseId]);

if (!$dose) {
    showFinalWarning('Nie znaleziono dawki.');
    return;
}

$save = pdo()->prepare('INSERT INTO medicine_application (cat_id, timestamp, medicine_id, dose, unit) VALUES (?, ?, ?, ?, ?)');
$saved = $save->execute([$dose['cat_id'], now(), $dose['medicine_id'], -$dose['dose'], $dose['unit']]);

if ($saved) {
    showInfo('Lek podany.');
} else {
    showError('Nie udało się podać leku!');
    showStatementError($save);
}


include '../../Shared/Views/Footer.php';