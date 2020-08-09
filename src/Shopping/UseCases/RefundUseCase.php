<?php
include '../../Shared/UseCases/UseCase.php';

$refundStatement = pdo()->prepare('update refund_plan set transfer_date = ? where transfer_date is null');
$refundSaved = $refundStatement->execute([date('Y-m-d H:i:s', time())]);

if ($refundSaved) {
    showInfo('Zwrot przelewem rozliczony.');

} else {
    showError('Nie udało się rozliczyć!');
    showStatementError($refundStatement);
}

include '../../Shared/Views/Footer.php';