<?php
include '../../Shared/UseCases/UseCase.php';

$catId = intval($_REQUEST['CatId']);

if (notValidId($catId)) {
    showFinalWarning('Nie wybrano kota.');
    return;
}

$save = pdo()->prepare('INSERT INTO pee (cat_id, timestamp) VALUES (?, ?)');
$saved = $save->execute([$catId, date('Y-m-d H:i:s')]);

if ($saved) {
    showInfo('Siku dodane.');
} else {
    showError('Nie udało się dodać siku!');
    showStatementError($save);
}

include '../../Shared/Views/Footer.php';