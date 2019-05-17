<?php

include __DIR__ . '/../../Configuration.php';

$catId = 1;

$save = $pdo->prepare('INSERT INTO pee (cat_id, timestamp) VALUES (?, ?)');
$saved = $save->execute([$catId, date('Y-m-d H:i:s')]);

if ($saved) {
    echo 'Siku dodane. ';
} else {
    echo 'Nie udało się dodać siku! ';
}