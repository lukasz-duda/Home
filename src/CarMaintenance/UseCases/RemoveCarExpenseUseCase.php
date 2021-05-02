<?php
include '../../Shared/UseCases/UseCase.php';

$id = intval($_REQUEST['Id']);

if (notValidId($id)) {
    showFinalWarning('Nie wybrano zakupu.');
    return;
}

$remove = pdo()->prepare('DELETE FROM car_expenses WHERE id = ?');
$removed = $remove->execute([$id]);

if ($removed) {
    showInfo('Zakup usunięty.');
} else {
    showError('Nie udało się usunąć zakupu!');
    showStatementError($remove);
}

include '../../Shared/Views/Footer.php';