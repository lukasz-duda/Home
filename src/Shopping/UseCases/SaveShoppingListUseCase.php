<?php
include '../../Shared/UseCases/UseCase.php';

$shoppingList = $_REQUEST['ShoppingList'];

if (notValidJson($shoppingList)) {
    showFinalWarning('Lista zakupów uszkodzona! Prześlij ponownie.');
}

$previous = json_decode(get('select s.json from shopping_list s', [])["json"]);
$requested = json_decode($shoppingList);

$newShoppingList = $requested;

if ($requested->previousVersion !== $previous->previousVersion) {
    foreach ($previous->items as $prevItem) {
        $exists = false;
        foreach ($newShoppingList->items as $newShoppingListItem) {
            if ($newShoppingListItem->name === $prevItem->name) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            array_push($newShoppingList->items, $prevItem);
        }
    }
}

$newShoppingList->previousVersion = uniqid();
$serialized = json_encode($newShoppingList);

$pdo = pdo();
$pdo->beginTransaction();

$saveShoppingList = pdo()->prepare('update shopping_list set json = ?');
$shoppingListSaved = $saveShoppingList->execute([$serialized]) && $pdo->commit();

if ($shoppingListSaved) {
    showInfo('Lista zakupów zapisana.');
} else {
    $pdo->rollBack();
    showError('Nie udało się zapisać listy zakupów!');
    showStatementError($saveShoppingList);
}

include '../../Shared/Views/Footer.php';