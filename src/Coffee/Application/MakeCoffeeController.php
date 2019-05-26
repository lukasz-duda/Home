<?php
include '../../Shared/Views/View.php';

$statement = $pdo->prepare('update coffees set current = current + 1');
$updated = $statement->execute([]);

if ($updated) {
    showInfo('Kawa zrobiona.');
} else {
    showError('Nie udało się zrobić kawy.');
    showStatementError($statement);
}

include '../../Shared/Views/Footer.php';