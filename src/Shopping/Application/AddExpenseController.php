<?php
include '../../Shared/Views/View.php';

$categoryId = $_REQUEST['CategoryId'] == '-1' ? null : intval($_REQUEST['CategoryId']);
$name = $_REQUEST['Name'];
$refund = $_REQUEST['Refund'];
$hasRefund = $refund != 'NoRefund';
$noRefund = !$hasRefund;
$value = $noRefund ? floatval($_REQUEST['Value']) : floatval($_REQUEST['Value']) / 2;

$saveExpenseStatement = $pdo->prepare('INSERT INTO expenses (timestamp, value, name, category_id) values (?, ?, ?, ?)');
$expenseSaved = $saveExpenseStatement->execute([date('Y-m-d H:i:s', time()), $value, $name, $categoryId]);

$expenseId = $pdo->lastInsertId();

if ($expenseSaved) {
    showMessage('Zakup dodany.');

    if ($noRefund) {
        return;
    }

    $forMe = $refund == 'RefundToMe' ? 1 : 0;

    $refundStatement = $pdo->prepare('INSERT INTO refund_plan (expense_id, for_me) values (?, ?)');
    $refundSaved = $refundStatement->execute([$expenseId, $forMe]);

    if (!$refundSaved) {
        showMessage('Nie udało się zaplanować zwrotu.');
        var_dump($refundStatement->errorInfo());
    } else {
        showMessage('Zwrot zaplanowany.');
    }

} else {
    showMessage('Nie udało się zapisać zakupu!');
}

include '../../Shared/Views/Footer.php';