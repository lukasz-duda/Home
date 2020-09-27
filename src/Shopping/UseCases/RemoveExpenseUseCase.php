<?php
include '../../Shared/UseCases/UseCase.php';

$id = intval($_REQUEST['Id']);

if (notValidId($id)) {
    showFinalWarning('Nie wybrano zakupu.');
    return;
}

$remove = pdo()->prepare('DELETE FROM expenses WHERE id = ?');
$removed = $remove->execute([$id]);

if ($removed) {
    showInfo('Zakup usunięty.');

    $hasRefund = get('SELECT expense_id FROM refund_plan WHERE expense_id = ?', [$id]);

    if ($hasRefund) {
        $removeRefund = pdo()->prepare('DELETE FROM refund_plan WHERE expense_id = ?');
        $refundRemoved = $removeRefund->execute([$id]);
        if ($refundRemoved) {
            showInfo('Zaplanowany zwrot usunięty.');
        } else {
            showError('Nie udało się usunąć zaplanowanego zwrotu!');
            showStatementError($removeRefund);
        }
    }
} else {
    showError('Nie udało się usunąć zakupu!');
    showStatementError($remove);
}

include '../../Shared/Views/Footer.php';