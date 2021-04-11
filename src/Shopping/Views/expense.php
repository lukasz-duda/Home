<?php
include '../../Shared/Views/View.php';
$expenseId = intval($_REQUEST['Id']);
$expense = get('select e.id, e.timestamp, e.name, e.value, c.name as category_name, r.for_me
from expenses e
   left join expense_categories c on c.id = e.category_id
left join refund_plan r on r.expense_id = e.id
where e.id = ?', [$expenseId]);
if ($expense['for_me'] == null) {
    $person = '';
} else {
    $person = ($expense['for_me']) ? 'Łukasz' : 'Ilona';
}
?>

    <h1>Zakup</h1>

    <p><?= showMoney($expense['value']); ?></p>
    <p><?= $expense['timestamp'] ?></p>
    <p><?= $expense['name'] ?> - <?= $expense['category_name'] ?></p>
    <p><?= $person ?></p>

    <form action="../UseCases/RemoveExpenseUseCase.php" method="post">
        <input type="hidden" name="Id" value="<?= $expenseId ?>">
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Usuń</button>
        </div>
    </form>

<?php
include '../../Shared/Views/Footer.php';
