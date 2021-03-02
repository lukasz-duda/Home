<?php
include '../../Shared/Views/View.php';

$forIlona = getAll('select e.timestamp, e.name, e.value, c.name as category_name
from expenses e
    join expense_categories c on c.id = e.category_id
    join refund_plan r on r.expense_id = e.id
where r.transfer_date is null
and r.for_me = 0
order by e.value desc, e.timestamp desc', []);

$forMe = getAll('select e.timestamp, e.name, e.value, c.name as category_name
from expenses e
    join expense_categories c on c.id = e.category_id
    join refund_plan r on r.expense_id = e.id
where r.transfer_date is null
and r.for_me = 1
order by e.value desc, e.timestamp desc', []);
?>
    <h1>Rozliczenie zakupów</h1>

    <div class="card mb-3">
        <div class="card-header">Do zwrotu dla Ilony</div>
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
        <div class="card-header">Do zwrotu dla Łukasza</div>
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
