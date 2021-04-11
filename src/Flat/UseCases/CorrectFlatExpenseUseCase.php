<?php
include '../../Shared/UseCases/UseCase.php';

$id = intval($_REQUEST['Id']);
$date = $_REQUEST['Date'];

if (notValidId($id)) {
    showFinalWarning('Nie wybrano wpłaty.');
}

if (notValidString($date)) {
    showFinalWarning('Nie podano daty.');
}

$update = pdo()->prepare('UPDATE flat_expense SET timestamp = ? where id = ?');

$updated = $update->execute([$date, $id]);

if ($updated) {
    showInfo('Wpłata poprawiona.');
} else {
    showError('Nie udało się poprawić wpłaty!');
    showStatementError($update);
}

include '../../Shared/Views/Footer.php';