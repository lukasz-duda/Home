<?php
include '../../Shared/Views/View.php';

$expenseId = intval($_REQUEST['Id']);
$expense = get('select e.id, e.timestamp, e.name, e.value, c.name as category_name, r.for_me, r.transfer_date
from expenses e
   left join expense_categories c on c.id = e.category_id
left join refund_plan r on r.expense_id = e.id
where e.id = ?', [$expenseId]);
if ($expense['for_me'] == null) {
    $refund = '';
} else {
    $person = $expense['for_me'] ? 'Łukasz' : 'Ilona';
    $transferDate = $expense['transfer_date'];
    $refund = $transferDate == null ?
        "Zaplanowany zwrot dla $person" :
        "Zwrot dla $person wykonany $transferDate";
}
?>

    <h1>Zakup</h1>

    <p><?= showMoney($expense['value']); ?></p>
    <p><?= $expense['timestamp'] ?></p>
    <p><?= $expense['name'] ?> - <?= $expense['category_name'] ?></p>
    <p><?= $refund ?></p>

    <form action="../UseCases/CorrectExpenseUseCase.php" method="post">
        <input type="hidden" name="Id" value="<?= $expenseId ?>">
        <div class="row mb-3">
            <div class="col-auto">
                <label class="col-form-label" for="Date">Data</label>
            </div>
            <div class="col-auto">
                <input id="Date" name="Date" class="form-control" type="date"
                       value="<?= substr($expense['timestamp'], 0, 10) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Popraw datę</button>
            </div>
        </div>
    </form>

<?php
if ($transferDate) {
    ?>
    <div class="form-group">
        <a class="btn btn-primary"
           href="past_settlement.php?transfer-date=<?= showDate(strtotime($transferDate)) ?>">Rozliczenie</a>
    </div>
    <?php
}
?>

    <form action="../UseCases/RemoveExpenseUseCase.php" method="post">
        <input type="hidden" name="Id" value="<?= $expenseId ?>">
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Usuń</button>
        </div>
    </form>

<?php
include '../../Shared/Views/Footer.php';
