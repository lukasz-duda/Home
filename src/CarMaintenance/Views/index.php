<?php
include '../../Shared/Views/View.php';
$carId = 1;
$car = get('SELECT name, mileage FROM cars WHERE id = ?', [$carId]);
$carName = $car['name'];
$carMileage = $car['mileage'];
$categories = getAll('SELECT id, name FROM companies order by name', []);
$today = date('Y-m-d');
$tasks = getAll('SELECT name
FROM car_tasks
WHERE priority <> 0
AND
(
	(
		(last_mileage + mileage_interval) <= 
		(
			SELECT mileage FROM cars WHERE id = ?
		)
		OR
        (
			last_mileage IS NULL
            AND mileage_interval IS NOT NULL
		)
	)
    OR
    (
		(
			DATE_ADD(last_execution_date, INTERVAL execution_interval MONTH) <= ?
		)
		OR
        (
			last_execution_date IS NULL
            AND execution_interval IS NOT NULL
		)
	)
)', [$carId, $today]);
$total = intval(get('SELECT sum(e.value) as value FROM car_expenses e WHERE car_id = ?', [$carId])['value']);
$carValue = intval(get('SELECT sum(e.value) as value FROM car_expenses e WHERE car_id = ? and name = ?', [$carId, 'Samochód'])['value']);
$carValue = intval(get('SELECT sum(e.value) as value FROM car_expenses e WHERE car_id = ? and name = ?', [$carId, 'Samochód'])['value']);
$fuelValue = intval(get('SELECT sum(e.value) as value FROM car_expenses e WHERE car_id = ? and name = ?', [$carId, 'Olej napędowy'])['value']);
$otherValue = intval($total - $carValue - $fuelValue);
$days = intval(get('select DATEDIFF(max(e.date), min(e.date)) as days from car_expenses e where car_id = ?', [$carId])['days']);
?>
    <h1><?= $carName ?></h1>
    <div class="card mb-3">
        <div class="card-header"> Wydatki</div>
        <div class="card-body">
            Wszystkie: <?= $total ?> zł<br/>
            Samochód: <?= $carValue ?> zł<br/>
            Paliwo: <?= $total - $carValue - $otherValue ?> zł<br/>
            Eksploatacja: <?= $total - $carValue - $fuelValue ?> zł<br/>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Wydatki miesięczne</div>
        <div class="card-body">
            Wszystkie: <?= intval($total / $days * 30) ?> zł<br/>
            Samochód: <?= intval($carValue / $days * 30) ?> zł<br/>
            Paliwo: <?= intval(($total - $carValue - $otherValue) / $days * 30) ?> zł<br/>
            Eksploatacja: <?= intval(($total - $carValue - $fuelValue) / $days * 30) ?> zł<br/>
        </div>
    </div>
    <h2>Zadania</h2>
    <ul class="list-group">
        <?php
        foreach ($tasks as $task) {
            ?>
            <li class="list-group-item"><?= $task['name'] ?></li>
            <?php
        }
        ?>
    </ul>
    <h2>Dodaj zakup</h2>
    <form action="../Application/AddCarExpenseController.php" method="post">
        <div class="form-group">
            <label for="Date">Data</label>
            <input class="form-control" id="Date" name="Date" type="date" value="<?= $today ?>"/>
        </div>
        <div class="form-group">
            <label for="CompanyId">Firma</label>
            <select class="form-control" id="CompanyId" name="CompanyId">
                <option value="-1">Nieokreślona</option>
                <?php
                foreach ($categories as $company) {
                    ?>
                    <option value="<?= $company['id'] ?>"><?= $company['name'] ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="form-group"><label for="Name">Nazwa</label>
            <input class="form-control" id="Name" name="Name" value="Olej napędowy"/>
        </div>
        <div class="form-group">
            <label for="Value">Wartość</label>
            <input class="form-control" id="Value" name="Value" type="number" step="0.01" data-bind="value: value"/>
        </div>
        <div class="form-group">
            <label for="FuelQuantity">Ilość paliwa</label>
            <input class="form-control" id="FuelQuantity" name="FuelQuantity" type="number" step="0.001"
                   data-bind="value: fuelQuantity"/>
        </div>
        <div class="form-group">
            <label for="UnitPrice">Cena jednostkowa</label>
            <input class="form-control" id="UnitPrice" type="text"
                   data-bind="value: unitPrice" readonly/>
        </div>
        <div class="form-group">
            <label for="Mileage">Przebieg</label>
            <input class="form-control" id="Mileage" name="Mileage" type="number" step="1" value="<?= $carMileage ?>"/>
        </div>
        <button class="btn btn-primary" type="submit">Zapisz</button>
        <script>
            function ViewModel() {
                var me = this;
                me.value = ko.observable(null);
                me.fuelQuantity = ko.observable(null);

                me.unitPrice = ko.computed(function () {
                    var unitPrice = me.value() / me.fuelQuantity();
                    var invalid = isNaN(unitPrice) || unitPrice < 0 || !isFinite(unitPrice);
                    return invalid ? '' : unitPrice.toFixed(2);
                });
            }

            var viewModel = new ViewModel();
            ko.applyBindings(viewModel);
        </script>
    </form>
<?php
include '../../Shared/Views/Footer.php';
?>