<?php
include "../../Shared/Views/View.php";
$catId = intval($_REQUEST['Id']);
$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);

if ($cat === false) {
    showFinalWarning('Nie znaleziono wybranego kota.');
}

$catName = $cat['name'];
$medicines = getAll('select m.id, m.name from medicine m', []);
$doses = getAll('select d.id, d.name, d.day_count, d.dose, d.unit, m.name as medicine_name, d.visible
from medicine_dose d
join medicine m on d.medicine_id = m.id
where d.cat_id = ?', [$catId]);
?>
<h1>Leki
    <?= $catName ?>
</h1>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Dodaj lek</div>
            <div class="card-body">
                <form action="../UseCases/AddMedicineUseCase.php" method="post">
                    <input type="hidden" name="CatId" value="<?= $catId ?>">
                    <div class="form-group">
                        <label for="NewMedicineName">Nazwa</label>
                        <input class="form-control" id="NewMedicineName" name="MedicineName" required minlength="3"
                            maxlength="30" />
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Dodaj dawkę</div>
            <div class="card-body">
                <form action="../UseCases/AddDoseUseCase.php" method="post">
                    <input type="hidden" name="CatId" value="<?= $catId ?>">
                    <div class="form-group">
                        <label for="Name">Nazwa dawki</label>
                        <input class="form-control" id="Name" name="Name" required minlength="3" maxlength="30" />
                    </div>
                    <div class="form-group">
                        <label for="MedicineId">Lek</label>
                        <select class="form-control" id="MedicineId" name="MedicineId">
                            <?php
                            foreach ($medicines as $medicine) {
                                ?>
                                <option value="<?= $medicine['id'] ?>"><?= $medicine['name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Dose">Ilość jednostek</label>
                        <input id="Dose" name="Dose" class="form-control" type="number" step="0.0001" min="0.0001"
                            max="250" required />
                    </div>
                    <div class="form-group">
                        <label for="Unit">Jednostka</label>
                        <input class="form-control" id="Unit" name="Unit" required minlength="2" maxlength="10"
                            value="mg" />
                    </div>
                    <div class="form-group">
                        <label for="DayCount">Liczba dawek dziennie</label>
                        <input id="DayCount" name="DayCount" class="form-control" type="number" step="1" min="1" max="3"
                            required value="1" />
                    </div>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="form-group">
            <label for="Search">Szukaj dawki</label>
            <div class="input-group mb-3">
                <input type="search" class="form-control" id="Search" data-bind="value: query, hasFocus: true"
                    aria-describedby="SearchIcon">
                <div class="input-group-append">
                    <span class="input-group-text" id="SearchIcon"><i class="material-icons-outlined"
                            style="font-size: inherit">search</i></span>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">Dawki</div>
            <div class="list-group list-group-flush" data-bind="foreach: matchingDoses">
                <div class="list-group-item">
                    <div class="mb-3">
                        <span data-bind="text: dayCount"></span>
                        x
                        <span data-bind="text: dose"></span>
                        <span data-bind="text: unit"></span>
                        <span data-bind="text: medicineName"></span>
                    </div>
                    <form action="../UseCases/ApplyMedicineUseCase.php" method="post" class="mr-2">
                        <input type="hidden" name="Id" data-bind="value: id">
                        <button class="btn btn-primary mb-3">
                            Podaj
                            <span data-bind="text: name"></span>
                        </button>
                    </form>
                    <form action="../UseCases/RemoveDoseUseCase.php" method="post" class="mr-2">
                        <input type="hidden" name="Id" data-bind="value: id">
                        <button type="submit" class="btn btn-outline-danger mb-3">
                            Usuń
                            <span data-bind="text: name"></span>
                        </button>
                    </form>
                    <form action="../UseCases/HideDoseUseCase.php" method="post" data-bind="visible: visible" class="mr-2">
                        <input type="hidden" name="Id" data-bind="value: id">
                        <button type="submit" class="btn btn-outline-dark mb-3">
                            Ukryj
                            <span data-bind="text: name"></span>
                        </button>
                    </form>
                    <form action="../UseCases/ShowDoseUseCase.php" method="post" data-bind="visible: !visible">
                        <input type="hidden" name="Id" data-bind="value: id">
                        <button type="submit" class="btn btn-outline-success mb-3">
                            Pokaż
                            <span data-bind="text: name"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function Dose(src) {
        const me = this;
        me.id = parseInt(src.id);
        me.name = src.name;
        me.medicineName = src.medicine_name;
        me.unit = src.unit;
        me.dose = parseFloat(src.dose);
        me.dayCount = parseInt(src.day_count);
        me.visible = Boolean(parseInt(src.visible));
    }

    const dosesSource = JSON.parse('<?= json_encode($doses) ?>');
    const doses = dosesSource.map(src => new Dose(src));

    function ViewModel() {
        const me = this;
        me.query = ko.observable('');
        me.matchingDoses = ko.computed(() => {
            return doses.filter(dose => dose.name.toLowerCase().includes(me.query().toLowerCase()));
        });
    }

    const viewModel = new ViewModel();
    ko.applyBindings(viewModel);
</script>
<?php
include '../../Shared/Views/Footer.php';