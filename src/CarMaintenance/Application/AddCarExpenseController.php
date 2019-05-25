<?php
include '../../Shared/Views/View.php';

$carId = 1;
$date = $_REQUEST['Date'];
$companyId = $_REQUEST['CompanyId'] == '-1' ? null : intval($_REQUEST['CompanyId']);
$name = $_REQUEST['Name'];
$value = floatval($_REQUEST['Value']);
$fuelQuantity = floatval($_REQUEST['FuelQuantity']);
$mileage = intval($_REQUEST['Mileage']);

$saveCarExpenseStatement = $pdo->prepare('INSERT INTO car_expenses (car_id, name, company_id, value, date, mileage, fuel_quantity) values (?, ?, ?, ?, ?, ?, ?) ');
$carExpenseSaved = $saveCarExpenseStatement->execute([$carId, $name, $companyId, $value, $date, $mileage, $fuelQuantity]);

if ($carExpenseSaved) {
    showMessage('Zakup dodany.');
    $updateMileage = $pdo->prepare('UPDATE cars SET mileage = ? WHERE id = ?');
    $mileageUpdated = $updateMileage->execute([$mileage, $carId]);
    if ($mileageUpdated) {
        showMessage('Przebieg zaktualizowany.');
    } else {
        showMessage('Nie udało się zaktualizować przebiegu.');
    }
} else {
    showMessage('Nie udało się zapisać zakupu!');
}

include '../../Shared/Views/Footer.php';