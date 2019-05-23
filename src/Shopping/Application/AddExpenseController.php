<?php
include __DIR__ . '/../../Configuration.php';

$categoryId = $_REQUEST['CategoryId'] == '-1' ? null : intval($_REQUEST['CategoryId']);
$name = $_REQUEST['Name'];
$value = floatval($_REQUEST['Value']);

$saveExpenseStatement = $pdo->prepare('INSERT INTO expenses (timestamp, value, name, category_id) values (?, ?, ?, ?)');
$expenseSaved = $saveExpenseStatement->execute([date('Y-m-d H:i:s', time()), $value, $name, $categoryId]);

if ($expenseSaved) {
    echo 'Zakup dodany. ';
} else {
    echo 'Nie udało się zapisać zakupu! ';
    var_dump($saveExpenseStatement->errorInfo());
}