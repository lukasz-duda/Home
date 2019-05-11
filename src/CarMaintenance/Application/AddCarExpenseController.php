<?php
include __DIR__ . '/../../Configuration.php';

$carId = 1;
$date = $_REQUEST['Date'];
$companyId = $_REQUEST['CompanyId'] == '-1' ? null : intval($_REQUEST['CompanyId']);
$name = $_REQUEST['Name'];
$value = floatval($_REQUEST['Value']);
$fuelQuantity = floatval($_REQUEST['FuelQuantity']);
$mileage = intval($_REQUEST['Mileage']);

$pdo = new PDO($dsn, $user, $password);
$pdo->query('SET NAMES utf8');
$pdo->query('SET CHARACTER_SET utf8_unicode_ci');
$saveCarExpenseStatement = $pdo->prepare('INSERT INTO car_expenses (car_id, name, company_id, value, date, mileage, fuel_quantity) values (?, ?, ?, ?, ?, ?, ?) ');
$carExpenseSaved = $saveCarExpenseStatement->execute([$carId, $name, $companyId, $value, $date, $mileage, $fuelQuantity]);

if ($carExpenseSaved) {
    echo 'Zakup dodany. ';
    $updateMileage = $pdo->prepare('UPDATE cars SET mileage = ? WHERE id = ?');
    $mileageUpdated = $updateMileage->execute([$mileage, $carId]);
    if ($mileageUpdated) {
        echo 'Przebieg zaktualizowany. ';
    } else {
        echo 'Nie udało się zaktualizować przebiegu. ';
    }
} else {
    echo 'Nie udało się zapisać zakupu! ';
}