<?php
include '../../Shared/UseCases/UseCase.php';

$id = intval($_REQUEST['Id']);

if (notValidId($id)) {
    showFinalWarning('Nie wybrano dawki.');
    return;
}

$remove = pdo()->prepare('DELETE FROM medicine_dose WHERE id = ?');
$removed = $remove->execute([$id]);

if ($removed) {
    showInfo('Dawka usunięta.');
} else {
    showError('Nie udało się usunąć dawki!');
    showStatementError($remove);
}

include '../../Shared/Views/Footer.php';