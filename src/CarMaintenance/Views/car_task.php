<?php
include '../../Shared/Views/View.php';
$carTaskId = intval($_REQUEST['Id']);
$carTask = get('select t.id, t.car_id, t.name, t.notes, t.execution_interval, t.mileage_interval, t.last_execution_date, t.last_mileage from car_tasks t where t.id = ?', [$carTaskId]);

if ($carTask === false) {
    showFinalWarning('Nie znaleziono czynności.');
}

$carId = $carTask['car_id'];
$car = get('select c.name, max(m.mileage) as mileage
from cars c
join mileage m on m.car_id = c.id
where c.id = ?', [$carId]);
$carMileage = $car['mileage'];

$executions = getAll('select e.date, e.mileage, e.notes from car_task_executed e
where car_task_id = ?
order by e.date desc', [$carTaskId]);
?>
    <h1><?= $car['name'] ?> - <?= $carTask['name'] ?></h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Podsumowanie</div>
                <div class="card-body">
                    <p>Interwał: <?= $carTask['execution_interval'] ?> miesięcy</p>
                    <p>Data ostatniego wykonania: <?= $carTask['last_execution_date'] ?></p>
                    <p>Interwał przebiegu: <?= showInt($carTask['mileage_interval']) ?></p>
                    <p>Ostatni przebieg: <?= showInt($carTask['last_mileage']) ?></p>
                    <p><?= $carTask['notes'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Wykonaj czynność</div>
                <div class="card-body">
                    <form action="../UseCases/ExecuteCarTaskUseCase.php" method="post">
                        <input type="hidden" name="CarTaskId" value="<?= $carTaskId ?>">
                        <div class="form-group">
                            <label class="form-label" for="Date">Data wykonania</label>
                            <input id="Date" name="Date" class="form-control" type="date" required
                                   value="<?= today() ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="Mileage">Przebieg</label>
                            <input class="form-control" id="Mileage" name="Mileage" type="number" required step="1"
                                   min="150000"
                                   max="999999" value="<?= $carMileage ?>"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="Notes">Notatka</label>
                            <textarea class="form-control" id="Notes" name="Notes" minlength="5"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Czynność wykonana</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">Wykonanie</div>
        <div class="list-group list-group-flush">
            <?php
            foreach ($executions as $execution) {
                ?>
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= $execution['date'] ?></h5>
                        <small><?= $execution['mileage'] ?> km</small>
                    </div>
                    <p class="mb-1"><?= $execution['notes'] ?></p>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
<?php
include '../../Shared/Views/Footer.php';