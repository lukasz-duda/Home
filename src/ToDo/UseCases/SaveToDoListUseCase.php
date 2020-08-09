<?php
include '../../Shared/UseCases/UseCase.php';

$name = $_REQUEST['Name'];
$toDoList = $_REQUEST['ToDoList'];

if (notValidString($name)) {
    showFinalWarning('Nie podano nazwy listy zadań.');
}

if (notValidJson($toDoList)) {
    showFinalWarning('Lista zadań uszkodzona! Prześlij ponownie.');
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