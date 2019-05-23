<?php
include __DIR__ . '/../../Configuration.php';

$refundStatement = $pdo->prepare('update refund_plan set transfer_date = ? where transfer_date is null');
$refundSaved = $refundStatement->execute([date('Y-m-d H:i:s', time())]);

if ($refundSaved) {
    echo 'Zwrot przelewem rozliczony. ';

} else {
    echo 'Nie udało się rozliczyć! ';
}