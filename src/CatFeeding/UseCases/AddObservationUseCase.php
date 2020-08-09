<?php
include '../../Shared/UseCases/UseCase.php';

$catId = intval($_REQUEST['CatId']);
$notes = $_REQUEST['Notes'];

if (notValidId($catId)) {
    showFinalWarning('Nie wybrano kota.');
}

if (notValidString($notes)) {
    showFinalWarning('Nie podano opisu.');
}

$save = pdo()->prepare('INSERT INTO observation (cat_id, timestamp, notes) VALUES (?, ?, ?)');
$saved = $save->execute([$catId, date('Y-m-d H:i:s'), $notes]);

if ($saved) {
    showInfo('Obserwacja dodana.');
} else {
    showError('Nie udało się dodać obserwacji!');
    showStatementError($save);
}

include '../../Shared/Views/Footer.php';