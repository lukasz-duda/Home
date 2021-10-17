<?php
include '../../Shared/UseCases/UseCase.php';

$medicineApplicationId = intval($_REQUEST['Id']);

if (notValidId($medicineApplicationId)) {
    showFinalWarning('Nie wybrano identyfikator podanego leku.');
    return;
}

$delete = pdo()->prepare('DELETE FROM medicine_application WHERE id = ?');
$deleted = $delete->execute([$medicineApplicationId]);

if ($deleted) {
    showInfo('Wycofano podany lek.');
} else {
    showError('Nie udało się wycofać podanego leku!');
    showStatementError($delete);
}


include '../../Shared/Views/Footer.php';