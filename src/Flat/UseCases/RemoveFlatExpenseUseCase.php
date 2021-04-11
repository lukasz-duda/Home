<?php
include '../../Shared/UseCases/UseCase.php';

$id = intval($_REQUEST['Id']);

if (notValidId($id)) {
    showFinalWarning('Nie wybrano wpłaty.');
    return;
}

$remove = pdo()->prepare('DELETE FROM flat_expense WHERE id = ?');
$removed = $remove->execute([$id]);

if ($removed) {
    showInfo('Wpłata usunięta.');
} else {
    showError('Nie udało się usunąć wpłaty!');
    showStatementError($remove);
}

include '../../Shared/Views/Footer.php';