<?php
include '../../Shared/UseCases/UseCase.php';

$categoryId = intval($_REQUEST['CategoryId']);
$name = $_REQUEST['Name'];
$refund = $_REQUEST['Refund'];
$timestamp = notValidDate($_REQUEST['Date']) ? date('Y-m-d H:i:s', time()) : $_REQUEST['Date'];

if (notValidId($categoryId)) {
    showFinalWarning('Nie wybrano kategorii.');
}

if (notValidString($name)) {
    showFinalWarning('Nie podano nazwy.');
}

switch ($refund) {
    case 'NoRefund':
        $value = floatval($_REQUEST['Value']);
        $noRefund = true;
        $forMe = 0;
        break;
    case 'RefundToMe':
        $value = floatval($_REQUEST['Value']) / 3;
        $noRefund = false;
        $forMe = 1;
        break;
    case 'RefundToIlona':
        $value = floatval($_REQUEST['Value']) * 2 / 3;
        $noRefund = false;
        $forMe = 0;
        break;
    case 'FullRefundToMe':
        $value = floatval($_REQUEST['Value']);
        $noRefund = false;
        $forMe = 1;
        break;
    case 'FullRefundToIlona':
        $value = floatval($_REQUEST['Value']);
        $noRefund = false;
        $forMe = 0;
        break;
    default:
        showFinalWarning('Wybrano niepoprawną opcję zwrotu.');
}

$saveExpenseStatement = pdo()->prepare('INSERT INTO expenses (timestamp, value, name, category_id) values (?, ?, ?, ?)');
$expenseSaved = $saveExpenseStatement->execute([$timestamp, $value, $name, $categoryId]);

$expenseId = pdo()->lastInsertId();

if ($expenseSaved) {
    showInfo('Zakup dodany.');

    if ($noRefund) {
        include '../../Shared/Views/Footer.php';
        return;
    }

    $refundStatement = pdo()->prepare('INSERT INTO refund_plan (expense_id, for_me) values (?, ?)');
    $refundSaved = $refundStatement->execute([$expenseId, $forMe]);

    if (!$refundSaved) {
        showError('Nie udało się zaplanować zwrotu.');
        showStatementError($refundStatement);
    } else {
        showInfo('Zwrot zaplanowany.');
    }

} else {
    showError('Nie udało się zapisać zakupu!');
    showStatementError($saveExpenseStatement);
}

include '../../Shared/Views/Footer.php';
