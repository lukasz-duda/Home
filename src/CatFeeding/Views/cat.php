<?php
include "../../Shared/Views/View.php";
$catId = intval($_REQUEST['Id']);
$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);

if ($cat === false) {
    showFinalWarning('Nie znaleziono wybranego kota.');
}

$catName = $cat['name'];
$foods = getAll('SELECT f.id, f.name
from food f
where f.visible = 1
order by name', [$catId]);
$meals = getAll('select m.id, f.name, m.start, m.start_weight
from meal m
    join food f on f.id = m.food_id
where m.end is null
  and m.cat_id = ?', [$catId]);

$yesterdayStart = showDateTime(strtotime(today() . '- 1 day + 4 hours'));
$todayStart = showDateTime(strtotime(today() . '+ 4 hours'));
$tomorrowStart = showDateTime(strtotime(today() . ' + 1 day + 4 hours'));
$lastMeals = getAll('select meals.*,
       (meals.start_weight - meals.end_weight) / meals.daily_demand_weight * 100 as daily_demand_percentage
from (
         select m.id,
                f.name,
                m.start,
                m.start_weight,
                m.end,
                m.end_weight,
                (
                    select d.weight
                    from daily_demand d
                    where d.cat_id = m.cat_id
                      and d.food_id = m.food_id
                    order by d.timestamp desc
                    limit 1
                ) as daily_demand_weight
         from meal m
                  join food f on f.id = m.food_id
         where m.end is not null
           and m.cat_id = ?
           and m.start >= ?
         order by m.start desc
     ) meals
order by meals.start desc', [$catId, $yesterdayStart]);

$lastPoop = get('select p.timestamp,
       timestampdiff(day, p.timestamp, ?) >= 3 as warning,
       timestampdiff(hour, p.timestamp, ?) / 24 as days 
from poop p
where p.cat_id = ?
order by p.timestamp desc
limit 1', [now(), now(), $catId]);
$lastPee = get('select p.timestamp,
       timestampdiff(day, p.timestamp, ?) >= 1 as warning,
       timestampdiff(hour, p.timestamp, ?) / 24 as days
from pee p
where p.cat_id = ?
order by p.timestamp desc
limit 1', [now(), now(), $catId]);
$dailyDemand = get('select sum(meals.meal_weight / meals.daily_demand_weight * 100) as total
from (
         select m.start_weight,
                m.end_weight,
                m.start_weight - m.end_weight as meal_weight,
                (
                    select d.weight
                    from daily_demand d
                    where d.cat_id = m.cat_id
                      and d.food_id = m.food_id
                    order by d.timestamp desc 
                    limit 1
                )                             as daily_demand_weight
         from meal m
         where m.cat_id = ?
           and m.start >= ? and m.start < ?
     ) meals', [$catId, $todayStart, $tomorrowStart]);
$yesterdayDemand = get('select sum(meals.meal_weight / meals.daily_demand_weight * 100) as total
from (
         select m.start_weight,
                m.end_weight,
                m.start_weight - m.end_weight as meal_weight,
                (
                    select d.weight
                    from daily_demand d
                    where d.cat_id = m.cat_id
                      and d.food_id = m.food_id
                    order by d.timestamp desc
                    limit 1
                )                             as daily_demand_weight
         from meal m
         where m.cat_id = ?
           and m.start >= ? and m.start < ?
     ) meals', [$catId, $yesterdayStart, $todayStart]);
$medicineDoses = getAll('select d.id,
       d.name,
       d.day_count * d.dose as expected,
       (
           select sum(ifnull(ma.dose, 0))
           from medicine_application ma
           where ma.medicine_id = d.medicine_id
             and ma.cat_id = ?
             and ma.timestamp >= ? and ma.timestamp < ?
       ) as applied,
       d.unit,
       m.name as medicine_name,
       (
           select ma2.id
           from medicine_application ma2
           where ma2.medicine_id = d.medicine_id
             and ma2.cat_id = ?
             and ma2.timestamp >= ? and ma2.timestamp < ?
           order by ma2.timestamp desc
           limit 1
       ) as last_medicine_application_id
from medicine_dose d
join medicine m on d.medicine_id = m.id
where d.cat_id = ?
and d.visible = 1', [$catId, $todayStart, $tomorrowStart, $catId, $todayStart, $tomorrowStart, $catId]);
$lastObservation = get('select timestamp, notes
from observation
where cat_id = ?
order by timestamp desc
limit 1', [$catId]);
$lastWeight = get('select date, weight
from weight
where cat_id = ?
order by date desc
limit 1', [$catId]);
?>
    <h1><?= $catName ?></h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Rozpocznij posiłek</div>
                <div class="card-body">
                    <form action="../UseCases/StartMealUseCase.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <div class="form-group">
                            <label for="MealFoodId">Pokarm</label>
                            <select class="form-control" id="MealFoodId" name="FoodId">
                                <?php
                                foreach ($foods as $food) {
                                    ?>
                                    <option value="<?= $food['id'] ?>"><?= $food['name'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="MealWeight">Waga przed posiłkiem [g]</label>
                            <input id="MealWeight" name="Weight" class="form-control" type="number" step="1" min="1"
                                   max="500" required/>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary">Rozpocznij</button>
                            <a class="btn btn-secondary" href="food.php?Id=<?= $catId ?>">Pokarm</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Zakończ posiłek</div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($meals as $meal) {
                            ?>
                            <form action="../UseCases/EndMealUseCase.php" method="post">
                                <input type="hidden" name="MealId" value="<?= $meal['id'] ?>"/>
                                <div class="form-group">
                                    <label for="Meal<?= $meal['id'] ?>Weight">Waga <?= $meal['name'] ?> po
                                        posiłku <?= $meal['start_weight'] ?> g
                                        rozpoczętym <?= $meal['start'] ?> [g]</label>
                                    <input id="Meal<?= $meal['id'] ?>Weight" name="Weight" class="form-control"
                                           type="number"
                                           step="1"
                                           min="0" max="500" required/>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Zakończ</button>
                                </div>
                            </form>
                            <form action="../UseCases/RemoveMealUseCase.php" method="post">
                                <input type="hidden" name="Id" value="<?= $meal['id'] ?>"/>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-outline-danger">Usuń</button>
                                </div>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Toaleta</div>
                <div class="card-body">
                    <form action="../UseCases/AddPeeUseCase.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Siku</button>
                        </div>
                    </form>
                    <form action="../UseCases/AddPoopUseCase.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Kupa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Podsumowanie dnia</div>
                <div class="card-body">
                    Teraz: <?= now() ?><br/>
                    Ostatnia kupa:
                    <?= $lastPoop['timestamp'] ?><br/>
                    <span class="<?= $lastPoop['warning'] == 1 ? 'text-danger' : 'text-info' ?>">(<?= showDecimal($lastPoop['days'], 1) ?> dni temu)</span>
                    <br/>
                    Ostatnie siku: <?= $lastPee['timestamp'] ?><br/>
                    <span class="<?= $lastPee['warning'] == 1 ? 'text-danger' : 'text-info' ?>"> (<?= showDecimal($lastPee['days'], 1) ?> dni temu)</span>
                    <br/>
                    Zapotrzebowanie dzisiaj: <?= showInt($dailyDemand['total']); ?> %<br/>
                    Zapotrzebowanie wczoraj: <?= showInt($yesterdayDemand['total']); ?> %<br/>
                    <br/>
                    <?php
                    if ($lastWeight) {
                        $lastWeightDate = $lastWeight['date'];
                        $lastWeightValue = showDecimal($lastWeight['weight'], 2) . ' kg';
                        echo "Ostatnia waga $lastWeightValue z dnia $lastWeightDate<br/>";
                    }
                    if ($lastObservation) {
                        $lastObservationTime = $lastObservation['timestamp'];
                        $lastObservationNotes = $lastObservation['notes'];
                        echo "Ostatnia obserwacja $lastObservationTime: $lastObservationNotes<br/>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Leki</div>
                <div class="card-body">
                    <?php
                    if ($medicineDoses) foreach ($medicineDoses as $dose) {
                        ?>
                        <div class="mb-3">Podano <?= $dose['medicine_name'] ?> <?= showDecimal($dose['applied'], 4) ?>
                            z <?= showDecimal($dose['expected'], 4) ?> <?= $dose['unit'] ?></div>
                        <?php
                        if ($dose['expected'] > $dose['applied']) {
                            ?>
                            <form action="../UseCases/ApplyMedicineUseCase.php" method="post">
                                <input type="hidden" name="Id" value="<?= $dose['id'] ?>">
                                <button class="btn btn-primary mb-3">Podaj <?= $dose['name'] ?></button>
                            </form>
                            <?php
                        }
                        if ($dose['applied'] > 0) {
                            ?>
                            <form action="../UseCases/UndoMedicineApplicationUseCase.php" method="post">
                                <input type="hidden" name="Id" value="<?= $dose['last_medicine_application_id'] ?>">
                                <button class="btn btn-success mb-3">Cofnij <?= $dose['name'] ?></button>
                            </form>
                            <?php
                        }
                    }
                    ?>
                    <div class="form-group">
                        <a href="medicine.php?Id=<?= $catId ?>" class="btn btn-secondary">Leki</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Ostatnie posiłki</div>
        <div class="list-group list-group-flush">
            <?php
            foreach ($lastMeals as $lastMeal) {
                ?>

                <a href="<?= './meal.php?Id=' . $lastMeal['id'] ?>"
                   class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= $lastMeal['name'] ?></h5>
                    </div>
                    <p class="mb-1"><?= $lastMeal['start_weight'] ?> - <?= $lastMeal['end_weight'] ?>
                        = <?= showInt($lastMeal['start_weight'] - $lastMeal['end_weight']) ?> g
                        = <?= showInt($lastMeal['daily_demand_percentage']) ?> %</p>
                    <small><?= $lastMeal['start'] ?> - <?= $lastMeal['end'] ?></small>
                </a>

                <?php
            }
            ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Popraw</div>
                <div class="card-body">
                    <form action="../UseCases/RevertPeeUseCase.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <input type="hidden" name="Timestamp" value="<?= $lastPee['timestamp'] ?>">
                        <button type="submit" class="btn btn-outline-danger mb-3">
                            Wycofaj siku <?= $lastPee['timestamp'] ?></button>
                    </form>
                    <form action="../UseCases/RevertPoopUseCase.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <input type="hidden" name="Timestamp" value="<?= $lastPoop['timestamp'] ?>">
                        <button type="submit" class="btn btn-outline-danger mb-3">
                            Wycofaj kupę <?= $lastPoop['timestamp'] ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Obserwacja</div>
        <div class="card-body">
            <form action="../UseCases/AddObservationUseCase.php" method="post">
                <input type="hidden" name="CatId" value="<?= $catId ?>">
                <div class="form-group">
                    <label for="ObservationNotes">Opis</label>
                    <textarea id="ObservationNotes" name="Notes" class="form-control" required minlength="5"
                              maxlength="250"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Waga kota</div>
        <div class="card-body">
            <form action="../UseCases/WeighCatUseCase.php" method="post">
                <input type="hidden" name="CatId" value="<?= $catId ?>">
                <div class="form-group">
                    <label for="Weight">Waga</label>
                    <input id="CatWeight" type="number" name="Weight" class="form-control" required min="2" step="0.01" max="5"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
<?php
include '../../Shared/Views/Footer.php';