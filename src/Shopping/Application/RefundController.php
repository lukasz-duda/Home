<?php
include '../../Shared/Views/View.php';

$refundStatement = $pdo->prepare('update refund_plan set transfer_date = ? where transfer_date is null');
$refundSaved = $refundStatement->execute([date('Y-m-d H:i:s', time())]);

if ($refundSaved) {
    showInfo('Zwrot przelewem rozliczony.');

} else {
    showInfo('Nie udało się rozliczyć!');
}

include '../../Shared/Views/Footer.php';