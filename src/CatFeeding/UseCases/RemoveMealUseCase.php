<?php
include '../../Shared/UseCases/UseCase.php';

$id = intval($_REQUEST['Id']);

if (notValidId($id)) {
    showFinalWarning('Nie wybrano posiłku.');
    return;
}

$remove = pdo()->prepare('DELETE FROM meal WHERE id = ?');
$removed = $remove->execute([$id]);

if ($removed) {
    showInfo('Posiłek usunięty.');
} else {
    showError('Nie udało się usunąć posiłku!');
    showStatementError($remove);
}

include '../../Shared/Views/Footer.php';