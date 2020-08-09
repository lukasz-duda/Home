<?php
include '../../Shared/UseCases/UseCase.php';

$name = $_REQUEST['Name'];
$person = $_REQUEST['Person'];
$value = floatval($_REQUEST['Value']);

if (notValidString($name)) {
    showFinalWarning('Nie podano nazwy.');
}

if (notValidString($person)) {
    showFinalWarning('Nie wybrano osoby.');
}

if (notValidValue($value)) {
    showFinalWarning('Podaj dodatnią wartość.');
}

$saveExpenseStatement = pdo()->prepare('INSERT INTO flat_expense (timestamp, value, name, person) values (?, ?, ?, ?)');
$expenseSaved = $saveExpenseStatement->execute([date('Y-m-d H:i:s', time()), $value, $name, $person]);

if ($expenseSaved) {
    showInfo('Wydatek dodany.');
} else {
    showError('Nie udało się zapisać zakupu!');
    showStatementError($saveExpenseStatement);
}

include '../../Shared/Views/Footer.php';