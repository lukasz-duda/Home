<?php
include '../../Shared/UseCases/UseCase.php';

$name = $_REQUEST['Name'];

if (notValidString($name)) {
    showFinalWarning('Nie podano nazwy.');
}

$addCompany = pdo()->prepare('INSERT INTO companies (name, visible) values (?, 1)');
$companyAdded = $addCompany->execute([$name]);

if ($companyAdded) {
    showInfo('Firma dodana.');
} else {
    showError('Nie udało się dodać firmy!');
    showStatementError($addCompany);
}

include '../../Shared/Views/Footer.php';