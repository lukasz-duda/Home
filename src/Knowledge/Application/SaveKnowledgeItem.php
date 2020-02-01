<?php
include '../../Shared/Views/View.php';

$id = intval($_REQUEST['Id']);
$header = $_REQUEST['Header'];
$content = $_REQUEST['Content'];
$keywords = $_REQUEST['Keywords'];

$saveKnowledgeItemStatement = $pdo->prepare('UPDATE knowledge_items SET header = ?, content = ?, keywords = ?, date = ? where id = ?');

$knowledgeItemSaved = $saveKnowledgeItemStatement->execute([$header, $content, $keywords, date('Y-m-d'), $id]);

if ($knowledgeItemSaved) {
    showInfo('Pozycja bazy wiedzy zapisana.');

} else {
    showError('Nie udało się zapisać pozycji bazy wiedzy!');
    showStatementError($saveKnowledgeItemStatement);
}

include '../../Shared/Views/Footer.php';