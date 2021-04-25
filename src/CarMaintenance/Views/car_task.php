<?php
include '../../Shared/Views/View.php';
$taskId = intval($_REQUEST['Id']);
$task = get('select t.id, t.name, t.notes, t.execution_interval, t.mileage_interval, t.last_execution_date, t.last_mileage from car_tasks t where t.id = ?', [$taskId]);

if ($task === false) {
    showFinalWarning('Nie znaleziono czynności.');
}
?>
    <h1>Czynność serwisowa samochodu</h1>
    <p><?= $task['name'] ?></p>
    <p>Interwał: <?= $task['execution_interval'] ?> miesięcy</p>
    <p>Data ostatniego wykonania: <?= $task['last_execution_date'] ?></p>
    <p>Interwał przebiegu: <?= showInt($task['mileage_interval']) ?></p>
    <p>Ostatni przebieg: <?= showInt($task['last_mileage']) ?></p>
    <p><?= $task['notes'] ?></p>
<?php
include '../../Shared/Views/Footer.php';