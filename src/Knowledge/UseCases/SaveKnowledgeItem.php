<?php
include '../../Shared/Views/View.php';

$id = intval($_REQUEST['Id']);
$newItem = $id == 0;
$header = $_REQUEST['Header'];
$content = $_REQUEST['Content'];
$keywords = $_REQUEST['Keywords'];

$saveKnowledgeItemStatement = $newItem ?
    $pdo->prepare('INSERT INTO knowledge_items (header, content, keywords, date) VALUES (?, ?, ?, ?)') :
    $pdo->prepare('UPDATE knowledge_items SET header = ?, content = ?, keywords = ? where id = ?');

$knowledgeItemSaved = $newItem ?
    $saveKnowledgeItemStatement->execute([$header, $content, $keywords, date('Y-m-d')]) :
    $saveKnowledgeItemStatement->execute([$header, $content, $keywords, $id]);

if ($knowledgeItemSaved) {
    showInfo('Pozycja bazy wiedzy zapisana.');

} else {
    showError('Nie udało się zapisać pozycji bazy wiedzy!');
    showStatementError($saveKnowledgeItemStatement);
}

include '../../Shared/Views/Footer.php';