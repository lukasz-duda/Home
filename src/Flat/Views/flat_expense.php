<?php
include '../../Shared/Views/View.php';

$expenseId = intval($_REQUEST['Id']);
$expense = get('select id, timestamp, person, name, value from flat_expense where id = ?', [$expenseId]);

if ($expense === false) {
    showFinalWarning('Nie znaleziono wybranej wpłaty.');
}

?>

    <h1>Wpłata na mieszkanie</h1>

    <p class="lead"><?= $expense['name'] ?></p>

    <p><?= showMoney($expense['value']); ?></p>
    <p><?= $expense['timestamp'] ?></p>

    <form action="../UseCases/CorrectFlatExpenseUseCase.php" method="post">
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

    <form action="../UseCases/RemoveFlatExpenseUseCase.php" method="post">
        <input type="hidden" name="Id" value="<?= $expenseId ?>">
        <button type="submit" class="btn btn-primary">Usuń</button>
    </form>

<?php
include '../../Shared/Views/Footer.php';