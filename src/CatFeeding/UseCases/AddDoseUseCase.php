<?php
include '../../Shared/UseCases/UseCase.php';

$catId = intval($_REQUEST['CatId']);
$name = $_REQUEST['Name'];
$unit = $_REQUEST['Unit'];
$medicineId = intval($_REQUEST['MedicineId']);
$dose = floatval($_REQUEST['Dose']);
$dayCount = intval($_REQUEST['DayCount']);

if (notValidId($catId)) {
    showFinalWarning('Nie wybrano kota.');
}

if (notValidString($name)) {
    showFinalWarning('Nie podano nazwy.');
}

if (notValidString($unit)) {
    showFinalWarning('Nie podano jednostki.');
}

if (notValidValue($dayCount)) {
    showFinalWarning('Nie podano liczby dawek dziennie');
}

$save = pdo()->prepare('INSERT INTO medicine_dose (cat_id, name, medicine_id, dose, unit, day_count) VALUES (?, ?, ?, ?, ?, ?)');
$saved = $save->execute([$catId, $name, $medicineId, $dose, $unit, $dayCount]);
$id = pdo()->lastInsertId();

if ($saved) {
    showInfo('Dawka dodana.');
} else {
    showError('Nie udało się dodać dawki!');
    showStatementError($save);
}

include '../../Shared/Views/Footer.php';