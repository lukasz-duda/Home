<?php
include '../../Shared/Views/View.php';

$expenseId = intval($_REQUEST['Id']);
$expense = get("select concat(e.name, ifnull(concat(' - ', c.name), '')) as name, e.value, e.timestamp, e.fuel_quantity
from car_expenses e
left outer join companies c on c.id = e.company_id
where e.id = ?", [$expenseId]);
$fuelPrice = $expense['fuel_quantity'] != null ?
    sprintf('%s l = %s/l', showInt($expense['fuel_quantity']), showMoney($expense['value'] / $expense['fuel_quantity'])) :
    '';
?>

    <h1>Zakup</h1>

    <p><?= showMoney($expense['value']); ?></p>
    <p><?= $expense['timestamp'] ?></p>
    <p><?= $expense['name'] ?></p>
    <p><?= $fuelPrice ?></p>

    <form action="../UseCases/CorrectCarExpenseUseCase.php" method="post">
        <input type="hidden" name="Id" value="<?= $expenseId ?>">
        <div class="row mb-3">
            <div class="col-auto">
                <label class="col-form-label" for="Date">Data</label>
            </div>
            <div class="col-auto">
                <input id="Date" name="Date" class="form-control" type="date"
                       value="<?= substr($expense['timestamp'], 0, 10) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Popraw datę</button>
            </div>
        </div>
    </form>

    <form action="../UseCases/RemoveCarExpenseUseCase.php" method="post">
        <input type="hidden" name="Id" value="<?= $expenseId ?>">
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Usuń</button>
        </div>
    </form>

<?php
include '../../Shared/Views/Footer.php';
