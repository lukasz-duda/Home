<?php
include '../../Shared/Views/View.php';

$name = $_REQUEST['Name'];
$toDoList = $_REQUEST['ToDoList'];

if (json_decode($toDoList) === null) {
    showError('Lista zakupów uszkodzona! Prześlij ponownie.');
    return;
}

/** @noinspection SqlWithoutWhere */
$saveToDoList = pdo()->prepare('update to_do_list set json = ?');
$toDoListSaved = $saveToDoList->execute([$toDoList, $name]);

if ($toDoListSaved) {
    showInfo('Lista zadań zapisana.');
} else {
    showError('Nie udało się zapisać listy zadań!');
    showStatementError($saveToDoList);
}

include '../../Shared/Views/Footer.php';