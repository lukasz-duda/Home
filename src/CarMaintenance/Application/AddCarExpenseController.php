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

$saveCarExpenseStatement = $pdo->prepare('INSERT INTO car_expenses (car_id, name, company_id, value, timestamp, mileage, fuel_quantity) values (?, ?, ?, ?, ?, ?, ?) ');
$carExpenseSaved = $saveCarExpenseStatement->execute([$carId, $name, $companyId, $value, date('Y-m-d H:i:s', time()), $mileage, $fuelQuantity]);

if ($carExpenseSaved) {
    showInfo('Zakup dodany.');
    $updateMileage = $pdo->prepare('UPDATE cars SET mileage = ? WHERE id = ?');
    $mileageUpdated = $updateMileage->execute([$mileage, $carId]);
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