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
    <h1>Leki <?= $catName ?></h1>

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
                                   maxlength="30"/>
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
                            <label for="Name">Dawka</label>
                            <input class="form-control" id="Name" name="Name" required minlength="3"
                                   maxlength="30"/>
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
                            <label for="Dose">Dawka</label>
                            <input id="Dose" name="Dose" class="form-control" type="number" step="0.0001" min="0.0001"
                                   max="99.9999" required/>
                        </div>
                        <div class="form-group">
                            <label for="Unit">Jednostka</label>
                            <input class="form-control" id="Unit" name="Unit" required minlength="2" maxlength="10"
                                   value="mg"/>
                        </div>
                        <div class="form-group">
                            <label for="DayCount">Liczba dawek dziennie</label>
                            <input id="DayCount" name="DayCount" class="form-control" type="number" step="1" min="1"
                                   max="3" required value="1"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Dodaj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Dawki</div>
                <div class="list-group list-group-flush">
                    <?php
                    foreach ($doses as $dose) {
                        ?>
                        <div class="list-group-item">
                            <div class="mb-3">
                                <?= $dose['day_count'] ?>
                                x <?= $dose['dose'] ?> <?= $dose['unit'] ?> <?= $dose['medicine_name'] ?></div>
                            <form action="../UseCases/RemoveDoseUseCase.php" method="post">
                                <input type="hidden" name="Id" value="<?= $dose['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger mb-3">
                                    Usuń <?= $dose['name'] ?></button>
                            </form>
                            <?php
                            if ($dose['visible']) {
                                ?>
                                <form action="../UseCases/HideDoseUseCase.php" method="post">
                                    <input type="hidden" name="Id" value="<?= $dose['id'] ?>">
                                    <button type="submit" class="btn btn-outline-dark mb-3">
                                        Ukryj <?= $dose['name'] ?></button>
                                </form>
                                <?php
                            } else {
                                ?>
                                <form action="../UseCases/ShowDoseUseCase.php" method="post">
                                    <input type="hidden" name="Id" value="<?= $dose['id'] ?>">
                                    <button type="submit" class="btn btn-outline-success mb-3">
                                        Pokaż <?= $dose['name'] ?></button>
                                </form>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
include '../../Shared/Views/Footer.php';
