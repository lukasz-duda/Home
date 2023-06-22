<?php
include '../../Shared/UseCases/UseCase.php';

$event = $_REQUEST['EventName'];

$events = array('cleaned', 'degreased', 'lubricated');

if (!in_array($event, $events)) {
    showFinalWarning("Nieobsługiwane zdarzenie $event");
    return;
}

$statement = pdo()->prepare("update coffee_machine set last_$event = ?");
$updated = $statement->execute([today()]);

if ($updated) {
    showInfo('Czynność wykonana.');
} else {
    showError('Nie udało się wykonać czynności.');
    showStatementError($statement);
}

include '../../Shared/Views/Footer.php';