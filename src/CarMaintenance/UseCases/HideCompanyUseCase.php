<?php
include '../../Shared/UseCases/UseCase.php';

$id = intval($_REQUEST['Id']);

if (notValidId($id)) {
    showFinalWarning('Nie wybrano firmy.');
    return;
}

$update = pdo()->prepare('update companies set visible = 0 where id = ?');
$updated = $update->execute([$id]);

if ($updated) {
    showInfo('Firma ukryta.');
} else {
    showError('Nie udało się ukryć firmy!');
    showStatementError($update);
}


include '../../Shared/Views/Footer.php';