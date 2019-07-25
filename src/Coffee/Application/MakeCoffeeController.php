<?php
include '../../Shared/Views/View.php';

$coffeesCount = intval($_REQUEST['CoffesCount']);

$statement = $pdo->prepare('update coffees set current = current + ?');
$updated = $statement->execute([$coffeesCount]);

if ($updated) {
    showInfo('Kawa zrobiona.');
} else {
    showError('Nie udało się zrobić kawy.');
    showStatementError($statement);
}

include '../../Shared/Views/Footer.php';