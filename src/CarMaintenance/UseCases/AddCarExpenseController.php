<?php
include '../../Shared/Views/View.php';

$carId = 1;
$companyId = $_REQUEST['CompanyId'] == '-1' ? null : intval($_REQUEST['CompanyId']);
$name = $_REQUEST['Name'];
$value = floatval($_REQUEST['Value']);
$fuelQuantity = floatval($_REQUEST['FuelQuantity']);
$mileage = intval($_REQUEST['Mileage']);

if ($fuelQuantity == 0)
    $fuelQuantity = null;

$saveCarExpenseStatement = pdo()->prepare('INSERT INTO car_expenses (car_id, name, company_id, value, timestamp, fuel_quantity) values (?, ?, ?, ?, ?, ?) ');
$carExpenseSaved = $saveCarExpenseStatement->execute([$carId, $name, $companyId, $value, date('Y-m-d H:i:s'), $fuelQuantity]);

if ($carExpenseSaved) {
    showInfo('Zakup dodany.');

    $currentMileageRow = get('SELECT MAX(m.mileage) as mileage FROM mileage m where m.car_id = ?', [$carId]);

    if ($mileage == 0 || $mileage == $currentMileageRow['mileage']) {
        showInfo('Przebieg niezmieniony.');
        return;
    }

    $updateMileage = pdo()->prepare('INSERT INTO mileage (car_id, date, mileage) values (?, ?, ?)');
    $mileageUpdated = $updateMileage->execute([$carId, date('Y-m-d'), $mileage]);
    if ($mileageUpdated) {
        showInfo('Przebieg zaktualizowany.');
    } else {
        showError('Nie udało się zaktualizować przebiegu.');
        showStatementError($updateMileage);
    }
} else {
    showError('Nie udało się zapisać zakupu!');
    showStatementError($saveCarExpenseStatement);
}

include '../../Shared/Views/Footer.php';