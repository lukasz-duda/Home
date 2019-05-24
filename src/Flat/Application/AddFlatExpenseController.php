<?php
include __DIR__ . '/../../Configuration.php';

$name = $_REQUEST['Name'];
$person = $_REQUEST['Person'];
$value = floatval($_REQUEST['Value']);

if ($person == null) {
    echo 'Wybierz osobę.';
    return;
}

$saveExpenseStatement = $pdo->prepare('INSERT INTO flat_expense (timestamp, value, name, person) values (?, ?, ?, ?)');
$expenseSaved = $saveExpenseStatement->execute([date('Y-m-d H:i:s', time()), $value, $name, $person]);

if ($expenseSaved) {
    echo 'Wydatek dodany.';
} else {
    echo 'Nie udało się zapisać zakupu! ';
}