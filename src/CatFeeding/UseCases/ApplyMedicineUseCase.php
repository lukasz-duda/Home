<?php
include '../../Shared/UseCases/UseCase.php';

$catId = intval($_REQUEST['CatId']);

if (notValidId($catId)) {
    showFinalWarning('Nie wybrano kota.');
    return;
}

$alreadyDone = get('SELECT * FROM home.medicine WHERE cat_id = ? and date = ?', [$catId, today()]);

if ($alreadyDone) {
    showWarning('Lek już dzisiaj podano.');
} else {
    $save = pdo()->prepare('INSERT INTO medicine (cat_id, date) VALUES (?, ?)');
    $saved = $save->execute([$catId, today()]);

    if ($saved) {
        showInfo('Lek podany.');
    } else {
        showError('Nie udało się podać leku!');
        showStatementError($save);
    }
}

include '../../Shared/Views/Footer.php';