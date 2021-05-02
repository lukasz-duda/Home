<?php
include '../../Shared/UseCases/UseCase.php';

$id = intval($_REQUEST['Id']);
$date = $_REQUEST['Date'];

if (notValidId($id)) {
    showFinalWarning('Nie wybrano zakupu.');
}

if (notValidString($date)) {
    showFinalWarning('Nie podano daty.');
}

$update = pdo()->prepare('UPDATE car_expenses SET timestamp = ? where id = ?');

$updated = $update->execute([$date, $id]);

if ($updated) {
    showInfo('Zakup poprawiony.');
} else {
    showError('Nie udało się poprawić zakupu!');
    showStatementError($update);
}

include '../../Shared/Views/Footer.php';