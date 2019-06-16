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
$lastExpenses = getAll('select e.timestamp, e.name, e.value, c.name as category_name, r.for_me
from expenses e
   left join expense_categories c on c.id = e.category_id
left join refund_plan r on r.expense_id = e.id
order by e.timestamp desc limit 10', [])
?>
    <h1>Zakupy</h1>

    <div class="card mb-3">
        <div class="card-header">Planowanie</div>
        <div class="card-body">
            <button class="btn-primary btn mb-3" data-bind="click: addShoppingItem">Dodaj</button>
            <div data-bind="foreach: shoppingList">
                <div class="form-group">

                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Rozliczenie</div>
        <div class="card-body">
            <p>
                Do zwrotu dla Ilony: <?= showMoney($forIlona['value']); ?><br/>
                Do zwrotu dla Łukasza: <?= showMoney($forMe['value']); ?><br/>
                Kwota do rozliczenia przez Łukasza: <?= showMoney($forIlona['value'] - $forMe['value']); ?>
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
                    <input class="form-control" id="Value" name="Value" type="number" step="0.01"/>
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
                            Zwróć połowę Łukaszowi
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
                    if ($expense['for_me'] == null) {
                        $person = '';
                    } else {
                        $person = ($expense['for_me']) ? 'Łukasz' : 'Ilona';
                    }
                    ?>

                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= showMoney($expense['value']); ?></h5>
                            <small><?= $expense['timestamp'] ?></small>
                        </div>
                        <p class="mb-1"><?= $expense['name'] ?> - <?= $expense['category_name'] ?></p>
                        <small><?= $person ?></small>
                    </a>
                    <?php
                }
                ?>

            </div>
        </div>
    </div>
    <script>
        function ViewModel() {
            var me = this;
            me.shoppingList = ko.observableArray([]);
            me.addShoppingItem = function () {
                me.shoppingList.push({name: ko.observable(null), done: ko.observable(false)});
            };
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel);
    </script>
<?php
include '../../Shared/Views/Footer.php';