<?php
include '../../Shared/UseCases/UseCase.php';

$name = $_REQUEST['MedicineName'];

if (notValidString($name)) {
    showFinalWarning('Nie podano nazwy.');
}

$saveMedicine = pdo()->prepare('INSERT INTO medicine (name) VALUES (?)');
$medicineSaved = $saveMedicine->execute([$name]);
$medicineId = pdo()->lastInsertId();

if ($medicineSaved) {
    showInfo('Lek dodany.');
} else {
    showError('Nie udało się dodać leku!');
    showStatementError($saveMedicine);
}

include '../../Shared/Views/Footer.php';