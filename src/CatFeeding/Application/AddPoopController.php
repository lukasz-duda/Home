<?php
include '../../Shared/Views/View.php';

$catId = 1;

$save = $pdo->prepare('INSERT INTO poop (cat_id, timestamp) VALUES (?, ?)');
$saved = $save->execute([$catId, date('Y-m-d H:i:s')]);

if ($saved) {
    showInfo('Kupa dodana.');
} else {
    showInfo('Nie udało się dodać kupy!');
}

include '../../Shared/Views/Footer.php';