<?php
include '../../Shared/Views/View.php';

$catId = 1;

$save = $pdo->prepare('INSERT INTO pee (cat_id, timestamp) VALUES (?, ?)');
$saved = $save->execute([$catId, date('Y-m-d H:i:s')]);

if ($saved) {
    showMessage('Siku dodane.');
} else {
    showMessage('Nie udało się dodać siku!');
}

include '../../Shared/Views/Footer.php';