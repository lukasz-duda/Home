<?php
include '../../Shared/UseCases/UseCase.php';

$catId = intval($_REQUEST['CatId']);
$timestamp = $_REQUEST['Timestamp'];

if (notValidId($catId)) {
    showFinalWarning('Nie wybrano kota.');
    return;
}

if (notValidString($timestamp)) {
    showFinalWarning('Nie podano czasu.');
    return;
}

$remove = pdo()->prepare('DELETE FROM pee WHERE cat_id = ? and timestamp = ?');
$removed = $remove->execute([$catId, $timestamp]);

if ($removed) {
    showInfo('Siku wycofane.');
} else {
    showError('Nie udało się wycofać siku!');
    showStatementError($remove);
}

include '../../Shared/Views/Footer.php';