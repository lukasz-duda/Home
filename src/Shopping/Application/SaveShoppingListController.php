<?php
include '../../Shared/Views/View.php';

$shoppingList = $_REQUEST['ShoppingList'];

if (json_decode($shoppingList) === null) {
    showError('Lista zakupów uszkodzona! Prześlij ponownie.');
    return;
}

$saveShoppingList = $pdo->prepare('update shopping_list set json = ?');
$shoppingListSaved = $saveShoppingList->execute([$shoppingList]);

if ($shoppingListSaved) {
    showInfo('Lista zakupów zapisana.');
} else {
    showError('Nie udało się zapisać listy zakupów!');
    showStatementError($saveShoppingList);
}

include '../../Shared/Views/Footer.php';