<?php
include '../../Shared/UseCases/UseCase.php';

$carTaskId = $_REQUEST['CarTaskId'];
$date = $_REQUEST['Date'];
$mileage = $_REQUEST['Mileage'];
$notes = $_REQUEST['Notes'];

if (notValidId($carTaskId)) {
    showFinalWarning('Nie wybrano czynnośći.');
}

if (notValidString($date)) {
    showFinalWarning('Nie podano daty.');
}

if (notValidValue($mileage)) {
    showFinalWarning('Podano niepoprawny przebieg.');
}

$add = pdo()->prepare('insert into car_task_executed (car_task_id, date, mileage, notes) values (?, ?, ?, ?)');
$added = $add->execute([$carTaskId, $date, $mileage, $notes]);

if ($added) {

    $update = pdo()->prepare('update car_tasks
set last_mileage = ?,
    last_execution_date = ?
where id = ?');
    $updated = $update->execute([$mileage, $date, $carTaskId]);

    if ($updated) {
        showInfo('Czynność wykonana.');
    } else {
        showError('Nie udało się zaktualizować czynności!');
        showStatementError($update);
    }

} else {
    showError('Nie udało się wykonać czynności!');
    showStatementError($add);
}

include '../../Shared/Views/Footer.php';