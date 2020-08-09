<?php
include '../../Shared/UseCases/UseCase.php';

$shoppingList = $_REQUEST['ShoppingList'];

if (notValidJson($shoppingList)) {
    showFinalWarning('Lista zakupów uszkodzona! Prześlij ponownie.');
}

/** @noinspection SqlWithoutWhere */
$saveShoppingList = pdo()->prepare('update shopping_list set json = ?');
$shoppingListSaved = $saveShoppingList->execute([$shoppingList]);

if ($shoppingListSaved) {
    showInfo('Lista zakupów zapisana.');
} else {
    showError('Nie udało się zapisać listy zakupów!');
    showStatementError($saveShoppingList);
}

include '../../Shared/Views/Footer.php';