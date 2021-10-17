<?php
include '../../Shared/Views/View.php';

$transferDate = $_REQUEST['transfer-date'];
$dayAfterTransfer = showDate(strtotime($transferDate . ' +1 day'));

$sumForIlona = get('select sum(e.value)
from expenses e
    join refund_plan r on r.expense_id = e.id
where r.transfer_date >= ? and r.transfer_date < ?
and r.for_me = 0', [$transferDate, $dayAfterTransfer])[0];

$sumForMe = get('select sum(e.value)
from expenses e
    join refund_plan r on r.expense_id = e.id
where r.transfer_date >= ? and r.transfer_date < ?
and r.for_me = 1', [$transferDate, $dayAfterTransfer])[0];

$forIlona = getAll('select e.id, e.timestamp, e.name, e.value, c.name as category_name
from expenses e
    join expense_categories c on c.id = e.category_id
    join refund_plan r on r.expense_id = e.id
where r.transfer_date >= ? and r.transfer_date < ?
and r.for_me = 0
order by e.value desc, e.timestamp desc', [$transferDate, $dayAfterTransfer]);

$forMe = getAll('select e.id, e.timestamp, e.name, e.value, c.name as category_name
from expenses e
    join expense_categories c on c.id = e.category_id
    join refund_plan r on r.expense_id = e.id
where r.transfer_date >= ? and r.transfer_date < ?
and r.for_me = 1
order by e.value desc, e.timestamp desc', [$transferDate, $dayAfterTransfer]);

$forIlonaByDate = getAll('select e.id, e.timestamp, e.name, e.value, c.name as category_name
from expenses e
    join expense_categories c on c.id = e.category_id
    join refund_plan r on r.expense_id = e.id
where r.transfer_date >= ? and r.transfer_date < ?
and r.for_me = 0
order by e.timestamp', [$transferDate, $dayAfterTransfer]);

$forMeByDate = getAll('select e.id, e.timestamp, e.name, e.value, c.name as category_name
from expenses e
    join expense_categories c on c.id = e.category_id
    join refund_plan r on r.expense_id = e.id
where r.transfer_date >= ? and r.transfer_date < ?
and r.for_me = 1
order by e.timestamp', [$transferDate, $dayAfterTransfer]);
?>
    <h1>Rozliczenie zakończone</h1>

    <div class="card mb-3">
        <div class="card-header">Podsumowanie</div>
        <div class="card-body">
            Do zwrotu dla Ilony: <?= showMoney($sumForIlona); ?><br/>
            Do zwrotu dla Łukasza: <?= showMoney($sumForMe); ?><br/>
            Kwota do rozliczenia przez Łukasza: <?= showMoney($sumForIlona - $sumForMe); ?>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Do zwrotu dla Ilony (data)</div>
        <div class="list-group list-group-flush">
            <?php
            foreach ($forIlonaByDate as $expense) {
                ?>

                <a href="expense.php?Id=<?= $expense['id'] ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= showMoney($expense['value']); ?></h5>
                        <small><?= $expense['timestamp'] ?></small>
                    </div>
                    <p class="mb-1"><?= $expense['name'] ?> - <?= $expense['category_name'] ?></p>
                </a>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Do zwrotu dla Ilony (kwota malejąco)</div>
        <div class="list-group list-group-flush">
            <?php
            foreach ($forIlona as $expense) {
                ?>

                <a href="expense.php?Id=<?= $expense['id'] ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= showMoney($expense['value']); ?></h5>
                        <small><?= $expense['timestamp'] ?></small>
                    </div>
                    <p class="mb-1"><?= $expense['name'] ?> - <?= $expense['category_name'] ?></p>
                </a>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Do zwrotu dla Łukasza (data)</div>
        <div class="list-group list-group-flush">
            <?php
            foreach ($forMeByDate as $expense) {
                ?>

                <a href="expense.php?Id=<?= $expense['id'] ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= showMoney($expense['value']); ?></h5>
                        <small><?= $expense['timestamp'] ?></small>
                    </div>
                    <p class="mb-1"><?= $expense['name'] ?> - <?= $expense['category_name'] ?></p>
                </a>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Do zwrotu dla Łukasza (kwota malejąco)</div>
        <div class="list-group list-group-flush">
            <?php
            foreach ($forMe as $expense) {
                ?>

                <a href="expense.php?Id=<?= $expense['id'] ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= showMoney($expense['value']); ?></h5>
                        <small><?= $expense['timestamp'] ?></small>
                    </div>
                    <p class="mb-1"><?= $expense['name'] ?> - <?= $expense['category_name'] ?></p>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
<?php
include '../../Shared/Views/Footer.php';