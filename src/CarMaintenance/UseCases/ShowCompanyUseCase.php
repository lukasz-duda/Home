<?php
include '../../Shared/UseCases/UseCase.php';

$id = intval($_REQUEST['Id']);

if (notValidId($id)) {
    showFinalWarning('Nie wybrano firmy.');
    return;
}

$update = pdo()->prepare('update companies set visible = 1 where id = ?');
$updated = $update->execute([$id]);

if ($updated) {
    showInfo('Firma pokazana.');
} else {
    showError('Nie udało się pokazać firmy!');
    showStatementError($update);
}


include '../../Shared/Views/Footer.php';