<?php
include '../../Shared/Views/View.php';

$ilona = get('select sum(e.value) as value from flat_expense e where e.person = ?', ['Ilona']);
$lukasz = get('select sum(e.value) as value from flat_expense e where e.person = ?', ['Łukasz']);
$last = getAll('select timestamp, person, name, value from flat_expense order by timestamp desc limit 10', []);
?>
    <h1>Mieszkanie</h1>

    <div class="card mb-3">
        <div class="card-header">Podsumowanie</div>
        <div class="card-body">
            Łukasz zapłacił: <?= showMoney($lukasz['value']); ?><br/>
            Ilona zapłaciła: <?= showMoney($ilona['value']) ?><br/>
            Ilona zwróci jeszcze: <?= showMoney(round(($lukasz['value'] - $ilona['value']) / 2, 2)); ?>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Nowa wpłata</div>
        <div class="card-body">
            <form action="../UseCases/AddFlatExpenseController.php" method="post">
                <div class="form-group">
                    <label for="Value">Wartość</label>
                    <input class="form-control" id="Value" name="Value" required type="number" step="0.01" min="5"
                           max="500000"
                           data-bind="value: value"/>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Person" id="Ilona" value="Ilona" checked>
                        <label class="form-check-label" for="Ilona">
                            Wpłaca Ilona
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Person" id="Lukasz" value="Łukasz">
                        <label class="form-check-label" for="Lukasz">
                            Wpłaca Łukasz
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group">
                        <label for="Name">Nazwa</label>
                        <input class="form-control" id="Name" name="Name" required minlength="5" maxlength="80"/>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Ostatnie wpłaty</div>
        <div class="list-group list-group-flush">
            <?php
            foreach ($last as $expense) {
                ?>

                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= showMoney($expense['value']); ?></h5>
                        <small><?= $expense['timestamp'] ?></small>
                    </div>
                    <p class="mb-1"><?= $expense['name'] ?></p>
                    <small><?= $expense['person'] ?>  </small>
                </a>

                <?php
            }
            ?>

        </div>
    </div>

<?php
include '../../Shared/Views/Footer.php';
?>