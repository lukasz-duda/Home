<?php
include '../../Shared/Views/View.php';
$carId = intval($_REQUEST['Id']);
$car = get('SELECT c.name, max(m.mileage) as mileage
FROM cars c
join mileage m on m.car_id = c.id
WHERE c.id = ?', [$carId]);
$carName = $car['name'];
$carMileage = $car['mileage'];
$companies = getAll('SELECT id, name FROM companies where visible = 1 order by name', []);
$today = date('Y-m-d');
$tasks = getAll('SELECT t.id, t.name
FROM car_tasks t
WHERE t.car_id = ? AND t.priority <> 0
AND
(
	(
		(t.last_mileage + t.mileage_interval) <= 
		(
			SELECT max(m.mileage) FROM mileage m WHERE m.car_id = ?
		)
		OR
        (
			t.last_mileage IS NULL
            AND t.mileage_interval IS NOT NULL
		)
	)
    OR
    (
		(
			DATE_ADD(t.last_execution_date, INTERVAL t.execution_interval MONTH) <= ?
		)
		OR
        (
			t.last_execution_date IS NULL
            AND t.execution_interval IS NOT NULL
		)
	)
)', [$carId, $carId, $today]);
$total = intval(get('SELECT sum(e.value) as value FROM car_expenses e WHERE car_id = ?', [$carId])['value']);
$carValue = intval(get('SELECT sum(e.value) as value FROM car_expenses e WHERE car_id = ? and name = ?', [$carId, 'Samochód'])['value']);
$fuelValue = intval(get('SELECT sum(e.value) as value FROM car_expenses e WHERE car_id = ? and name = ?', [$carId, 'Olej napędowy'])['value']);
$otherValue = intval($total - $carValue - $fuelValue);
$days = intval(get('select DATEDIFF(max(e.timestamp), min(e.timestamp)) as days from car_expenses e where car_id = ?', [$carId])['days']);
$lastExpenses = getAll("select e.id, concat(e.name, ifnull(concat(' - ', c.name), '')) as name, e.value, e.timestamp, e.fuel_quantity
from car_expenses e
left outer join companies c on c.id = e.company_id
where e.car_id = ?
order by e.timestamp desc limit 10", [$carId]);
?>

<h1><?= $carName ?></h1>

<div class="card mb-3">
    <div class="card-header">Wydatki</div>
    <div class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action" href="Reports/car_annual.php?Id=<?= $carId ?>">Roczne
            wydatki na samochód</a>
    </div>
    <div class="card-body">
        Wszystkie: <?= showMoney($total); ?><br/>
        Samochód: <?= showMoney($carValue); ?><br/>
        Paliwo: <?= showMoney($total - $carValue - $otherValue); ?><br/>
        Eksploatacja: <?= showMoney($total - $carValue - $fuelValue); ?>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Wydatki miesięczne</div>
    <div class="card-body">
        Wszystkie: <?= $days ? showMoney($total / $days * 30) : ''; ?><br/>
        Samochód: <?= $days ? showMoney($carValue / $days * 30) : ''; ?><br/>
        Paliwo: <?= $days ? showMoney(($total - $carValue - $otherValue) / $days * 30) : ''; ?><br/>
        Eksploatacja: <?= $days ? showMoney(($total - $carValue - $fuelValue) / $days * 30) : ''; ?>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Zadania</div>
    <ul class="list-group list-group-flush">
        <?php
        foreach ($tasks as $task) {
            ?>
            <a href="car_task.php?Id=<?= $task['id'] ?>" class="list-group-item list-group-item-action">
                <?= $task['name'] ?></a>
            <?php
        }
        ?>
    </ul>
</div>

<div class="card mb-3">
    <div class="card-header">Dodaj zakup</div>
    <div class="card-body">
        <form action="../UseCases/AddCarExpenseUseCase.php" method="post">
            <input type="hidden" name="CarId" value="<?= $carId ?>">
            <div class="form-group"><label for="Name">Nazwa</label>
                <input class="form-control" id="Name" name="Name" value="Olej napędowy" required minlength="5"
                       maxlength="100"/>
            </div>
            <div class="form-group">
                <label for="Value">Wartość</label>
                <input class="form-control" id="Value" name="Value" type="number" step="0.01" min="1" max="10000"
                       data-bind="value: value" required/>
            </div>
            <div class="form-group">
                <label for="FuelQuantity">Ilość paliwa</label>
                <input class="form-control" id="FuelQuantity" name="FuelQuantity" type="number" step="0.001" min="5"
                       max="70"
                       data-bind="value: fuelQuantity"/>
            </div>
            <div class="form-group">
                <label for="UnitPrice">Cena jednostkowa</label>
                <input class="form-control" id="UnitPrice" type="text"
                       data-bind="value: unitPrice" readonly/>
            </div>
            <div class="form-group">
                <label for="Mileage">Przebieg</label>
                <input class="form-control" id="Mileage" name="Mileage" type="number" step="1" min="150000"
                       max="999999"
                       value="<?= $carMileage ?>"/>
            </div>
            <div class="form-group">
                <label for="CompanyId">Firma</label>
                <select class="form-control" id="CompanyId" name="CompanyId">
                    <option value="-1">Nieokreślona</option>
                    <?php
                    foreach ($companies as $company) {
                        ?>
                        <option value="<?= $company['id'] ?>"><?= $company['name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Zapisz</button>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Ostatnie zakupy</div>
    <div class="list-group list-group-flush">
        <?php
        foreach ($lastExpenses as $expense) {
            $fuelPrice = $expense['fuel_quantity'] != null ?
                sprintf('%s l = %s/l', showInt($expense['fuel_quantity']), showMoney($expense['value'] / $expense['fuel_quantity'])) :
                '';
            ?>

            <a href="car_expense.php?Id=<?= $expense['id'] ?>" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?= showMoney($expense['value']); ?></h5>
                    <small><?= $expense['timestamp'] ?></small>
                </div>
                <p class="mb-1"><?= $expense['name'] ?></p>
                <small><?= $fuelPrice ?></small>
            </a>
            <?php
        }
        ?>

    </div>
</div>

<script>
    function ViewModel() {
        const me = this;
        me.value = ko.observable(null);
        me.fuelQuantity = ko.observable(null);

        me.unitPrice = ko.computed(function () {
            const unitPrice = me.value() / me.fuelQuantity();
            const invalid = isNaN(unitPrice) || unitPrice < 0 || !isFinite(unitPrice);
            return invalid ? '' : unitPrice.toFixed(2);
        });
    }

    const viewModel = new ViewModel();
    ko.applyBindings(viewModel);
</script>
<?php
include '../../Shared/Views/Footer.php';
?>
