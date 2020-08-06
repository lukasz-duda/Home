<?php
include '../../Shared/Views/View.php';

$id = intval($_REQUEST['Id']);

$deleteKnowledgeItemStatement = pdo()->prepare('DELETE FROM knowledge_items where id = ?');
$knowledgeItemDeleted = $deleteKnowledgeItemStatement->execute([$id]);

if ($knowledgeItemDeleted) {
    showInfo('Pozycja bazy wiedzy usunięta.');

} else {
    showError('Nie udało się usunąć pozycji bazy wiedzy!');
    showStatementError($deleteKnowledgeItemStatement);
}

include '../../Shared/Views/Footer.php';