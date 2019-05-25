<?php
include '../../Shared/Views/View.php';

$catId = 1;
$notes = $_REQUEST['notes'];

$save = $pdo->prepare('INSERT INTO observation (cat_id, timestamp, notes) VALUES (?, ?, ?)');
$saved = $save->execute([$catId, date('Y-m-d H:i:s'), $notes]);

if ($saved) {
    showInfo('Obserwacja dodana.');
} else {
    showError('Nie udało się dodać obserwacji!');
    showStatementError($save);
}

include '../../Shared/Views/Footer.php';