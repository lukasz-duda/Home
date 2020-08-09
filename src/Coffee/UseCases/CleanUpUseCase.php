<?php
include '../../Shared/UseCases/UseCase.php';

/** @noinspection SqlWithoutWhere */
$statement = pdo()->prepare('update coffees set last_cleaning = current');
$updated = $statement->execute([]);

if ($updated) {
    showInfo('Ekspres umyty.');
} else {
    showError('Nie udało się umyć ekspresu.');
    showStatementError($statement);
}

include '../../Shared/Views/Footer.php';