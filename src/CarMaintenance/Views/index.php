<?php
include '../../Shared/Views/View.php';
$carId = 1;
$car = get('SELECT c.name, max(m.mileage) as mileage
FROM cars c
join mileage m on m.car_id = c.id
WHERE id = ?', [$carId]);
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
			SELECT max(m.mileage) FROM mileage m WHERE m.car_id = ?
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
$fuelValue = intval(get('SELECT sum(e.value) as value FROM car_expenses e WHERE car_id = ? and name = ?', [$carId, 'Olej napędowy'])['value']);
$otherValue = intval($total - $carValue - $fuelValue);
$days = intval(get('select DATEDIFF(max(e.timestamp), min(e.timestamp)) as days from car_expenses e where car_id = ?', [$carId])['days']);
$lastExpenses = getAll("select concat(e.name, ifnull(concat(' - ', c.name), '')) as name, e.value, e.timestamp, e.fuel_quantity
from car_expenses e
left outer join companies c on c.id = e.company_id
where e.car_id = ?
order by e.timestamp desc limit 10", [$carId]);
?>
    <h1><?= $carName ?></h1>

    <div class="card mb-3">
        <div class="card-header">Wydatki</div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action" href="reports.php">Raporty samochodu</a>
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
            Wszystkie: <?= showMoney($total / $days * 30); ?><br/>
            Samochód: <?= showMoney($carValue / $days * 30); ?><br/>
            Paliwo: <?= showMoney(($total - $carValue - $otherValue) / $days * 30); ?><br/>
            Eksploatacja: <?= showMoney(($total - $carValue - $fuelValue) / $days * 30); ?>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Zadania</div>
        <ul class="list-group list-group-flush">
            <?php
            foreach ($tasks as $task) {
                ?>
                <li class="list-group-item"><?= $task['name'] ?></li>
                <?php
            }
            ?>
        </ul>
    </div>

    <div class="card mb-3">
        <div class="card-header">Dodaj zakup</div>
        <div class="card-body">
            <form action="../Application/AddCarExpenseController.php" method="post">
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
                    <input class="form-control" id="Mileage" name="Mileage" type="number" step="1" min="250000"
                           max="999999"
                           value="<?= $carMileage ?>"/>
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

                <a href="#" class="list-group-item list-group-item-action">
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
<?php
include '../../Shared/Views/Footer.php';
?>