<?php

include __DIR__ . '/../../Configuration.php';

$catId = 1;
$notes = $_REQUEST['notes'];
$pdo = newPdo();

$save = $pdo->prepare('INSERT INTO observation (cat_id, timestamp, notes) VALUES (?, ?, ?)');
$saved = $save->execute([$catId, date('Y-m-d H:i:s'), $notes]);

if ($saved) {
    echo 'Obserwacja dodana. ';
} else {
    echo 'Nie udało się dodać obserwacji! ';
}