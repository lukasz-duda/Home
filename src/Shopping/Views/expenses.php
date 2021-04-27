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
$categories = getAll('select id, name from expense_categories order by name', []);
$lastExpenses = getAll('select e.id, e.timestamp, e.name, e.value, c.name as category_name, r.for_me
from expenses e
   left join expense_categories c on c.id = e.category_id
left join refund_plan r on r.expense_id = e.id
where e.timestamp >= ?
order by e.timestamp desc', [date('Y-m-d', strtotime('-21 days'))]);
?>
    <h1>Zakupy</h1>

    <div class="card mb-3">
        <div class="card-header">Dodaj</div>
        <div class="card-body">
            <form action="../UseCases/AddExpenseUseCase.php" method="post">
                <div class="form-group">
                    <label for="Value">Wartość zakupu</label>
                    <input class="form-control" id="Value" name="Value" type="number" step="0.01" min="1" max="500000"
                           required data-bind="value: expenseValue"/>
                </div>
                <div class="form-group">
                    <label for="Calculation">Oblicz wartość zakupu</label>
                    <div class="input-group mb-3">
                        <input class="form-control" id="Calculation" name="Calculation" placeholder="2+2"
                               data-bind="value: expenseValueCalculation"/>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button"
                                    data-bind="click: calculateExpenseValue">Oblicz
                            </button>
                        </div>
                    </div>
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
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Refund" id="FullRefundToMe"
                               value="FullRefundToMe">
                        <label class="form-check-label" for="FullRefundToMe">
                            Zwróć całość Łukaszowi
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Refund" id="FullRefundToIlona"
                               value="FullRefundToIlona">
                        <label class="form-check-label" for="FullRefundToIlona">
                            Zwróć całość Ilonie
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group">
                        <label for="Name">Nazwa</label>
                        <input class="form-control" id="Name" name="Name" minlength="2" maxlength="50" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="CategoryId">Kategoria</label>
                    <select class="form-control" id="CategoryId" name="CategoryId" required>
                        <option value="">Wybierz</option>
                        <?php
                        foreach ($categories as $category) {
                            ?>
                            <option <?= $category['name'] == 'Jedzenie' ? 'selected' : '' ?>
                                    value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
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
        <div class="card-header">Rozliczenie</div>
        <div class="card-body">
            <p>
                Do zwrotu dla Ilony: <?= showMoney($forIlona['value']); ?><br/>
                Do zwrotu dla Łukasza: <?= showMoney($forMe['value']); ?><br/>
                Kwota do rozliczenia przez Łukasza: <?= showMoney($forIlona['value'] - $forMe['value']); ?>
            </p>
            <form action="../UseCases/RefundUseCase.php" method="post">
                <div class="form-group">
                    <a class="btn btn-primary" href="settlement.php">Podsumowanie</a>
                    <button class="btn btn-secondary">Rozlicz</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Ostatnie zakupy</div>
        <div class="list-group list-group-flush">
            <?php
            foreach ($lastExpenses as $expense) {
                if ($expense['for_me'] == null) {
                    $person = '';
                } else {
                    $person = ($expense['for_me']) ? 'Łukasz' : 'Ilona';
                }
                ?>

                <a href="expense.php?Id=<?= $expense['id'] ?>" class="list-group-item list-group-item-action">
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
    <script>
        function ViewModel() {
            const me = this;

            me.expenseValue = ko.observable(null);

            me.expenseValueCalculation = ko.observable(null);

            me.calculateExpenseValue = function () {
                const expression = me.expenseValueCalculation().split(',').join('.');

                const calculationRegExp = /^[0-9+-.()*\/]*$/;
                if (calculationRegExp.test(expression) === false) {
                    return;
                }

                const result = eval(expression);
                const currency = isNaN(result) ? 0 : Math.round(result * 100) / 100;
                me.expenseValue(currency);
            };
        }

        const viewModel = new ViewModel();
        ko.applyBindings(viewModel);
    </script>
<?php
include '../../Shared/Views/Footer.php';
