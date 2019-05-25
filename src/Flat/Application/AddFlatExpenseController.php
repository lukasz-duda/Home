<?php
include '../../Shared/Views/View.php';

$name = $_REQUEST['Name'];
$person = $_REQUEST['Person'];
$value = floatval($_REQUEST['Value']);

if ($person == null) {
    showMessage('Wybierz osobę.');
    return;
}

$saveExpenseStatement = $pdo->prepare('INSERT INTO flat_expense (timestamp, value, name, person) values (?, ?, ?, ?)');
$expenseSaved = $saveExpenseStatement->execute([date('Y-m-d H:i:s', time()), $value, $name, $person]);

if ($expenseSaved) {
    showMessage('Wydatek dodany.');
} else {
    showMessage('Nie udało się zapisać zakupu!');
}

include '../../Shared/Views/Footer.php';