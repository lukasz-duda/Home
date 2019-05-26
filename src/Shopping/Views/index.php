<?php
include '../../Shared/Views/View.php';

$forIlona = get('select sum(e.value) as value
           from refund_plan r
                    join expenses e on e.id = r.expense_id
           where r.for_me = 0
             and r.transfer_date is null', []);
$forMe = get('select sum(e.value) as value
           from refund_plan r
               join expenses e on e.id = r.expense_id
           where r.for_me = 1
             and r.transfer_date is null', []);
$categories = getAll('select id, name from expense_categories', []);
$lastExpenses = getAll('select e.timestamp, e.name, e.value, c.name as category_name from expenses e join expense_categories c on c.id = e.category_id order by e.timestamp desc limit 3', [])
?>
    <h1>Zakupy</h1>

    <div class="card mb-3">
        <div class="card-header">Rozliczenie</div>
        <div class="card-body">
            <p>
                Do zwrotu dla Ilony: <?= showMoney($forIlona['value']); ?><br/>
                Do zwrotu dla mnie: <?= showMoney($forMe['value']); ?><br/>
                Kwota do rozliczenia: <?= showMoney($forIlona['value'] - $forMe['value']); ?>
            </p>
            <form action="../Application/RefundController.php" method="post">
                <div class="form-group">
                    <button class="btn btn-primary">Rozlicz</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Dodaj</div>
        <div class="card-body">
            <form action="../Application/AddExpenseController.php" method="post">
                <div class="form-group">
                    <label for="Value">Wartość zakupu</label>
                    <input class="form-control" id="Value" name="Value" type="number" step="0.01"
                           data-bind="value: value"/>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Refund" id="NoRefund" value="NoRefund"
                               checked>
                        <label class="form-check-label" for="NoRefund">
                            Zwykły zakup
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Refund" id="RefundToMe" value="RefundToMe">
                        <label class="form-check-label" for="RefundToMe">
                            Zwróć połowę mnie
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Refund" id="RefundToIlona"
                               value="RefundToIlona">
                        <label class="form-check-label" for="RefundToIlona">
                            Zwróć połowę Ilonie
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group">
                        <label for="Name">Nazwa</label>
                        <input class="form-control" id="Name" name="Name"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="CategoryId">Firma</label>
                    <select class="form-control" id="CategoryId" name="CategoryId">
                        <?php
                        foreach ($categories as $category) {
                            ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Zapisz</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Ostatnie zakupy</div>
        <div class="card-body">
            <div class="list-group">
                <?php
                foreach ($lastExpenses as $expense) {
                    ?>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= showMoney($expense['value']); ?></h5>
                            <small><?= $expense['timestamp'] ?></small>
                        </div>
                        <p class="mb-1"><?= $expense['name'] ?></p>
                        <small><?= $expense['category_name'] ?></small>
                    </a>
                    <?php
                }
                ?>

            </div>
        </div>
    </div>
<?php
include '../../Shared/Views/Footer.php';