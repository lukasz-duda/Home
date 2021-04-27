<?php
include '../../Shared/UseCases/UseCase.php';

$twoWeeksAgo = showDate(strtotime(today() . ' -2 weeks'));

$update = pdo()->prepare('update food set visible = 0
where id not in (
    select distinct
	m.food_id
from meal m
where m.start >= ?
)');
$updated = $update->execute([$twoWeeksAgo]);

if ($updated) {
    showInfo("Ukryte pokarmy nieużywane po $twoWeeksAgo.");
} else {
    showError('Nie udało się ukryć pokarmów!');
    showStatementError($update);
}

include '../../Shared/Views/Footer.php';