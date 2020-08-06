<?php
include "../../Shared/Views/View.php";
$catId = intval($_REQUEST['Id']);
$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);
$catName = $cat['name'];
$foods = getAll('SELECT f.id, f.name, f.description, f.visible,
(
    select d.weight
    from daily_demand d
    where d.food_id = f.id
      and d.cat_id = ?
    order by d.timestamp desc 
    limit 1
) as weight
FROM food f
order by name', [$catId]);
$meals = getAll('select m.id, f.name, m.start, m.start_weight
from meal m
    join food f on f.id = m.food_id
where m.end is null
  and m.cat_id = ?', [$catId]);

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
order by meals.start desc', [$catId, date('Y-m-d', strtotime('-2 days'))]);

$lastPoop = get('select p.timestamp,
       timestampdiff(day, p.timestamp, ?) >= 3 as warning
from poop p
where p.cat_id = ?
order by p.timestamp desc
limit 1', [now(), $catId]);
$lastPee = get('select p.timestamp,
       timestampdiff(day, p.timestamp, ?) >= 1 as warning
from pee p
where p.cat_id = ?
order by p.timestamp desc
limit 1', [now(), $catId]);
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
                  join food f on m.food_id = f.id
         where m.cat_id = ?
           and datediff(m.start, ?) = 0
     ) meals', [$catId, today()]);
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
                  join food f on m.food_id = f.id
         where m.cat_id = ?
           and datediff(m.start, ?) = 0
     ) meals', [$catId, date('Y-m-d', strtotime('-1 days'))]);
$medicineApplied = get('select exists(
               select 1
               from medicine
               where cat_id = ?
                 and date = ?
           )
           as medicine_applied', [$catId, today()])[0] == 1;
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
                    <form action="../UseCases/StartMealController.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <div class="form-group">
                            <label for="MealFoodId">Pokarm</label>
                            <select class="form-control" id="MealFoodId" name="FoodId"
                                    data-bind="options: visibleFoods, optionsText: 'name', optionsValue: 'id', value: startMealfoodId"> </select>
                        </div>
                        <div class="form-group">
                            <label for="MealWeight">Waga przed posiłkiem [g]</label>
                            <input id="MealWeight" name="Weight" class="form-control" type="number" step="1" min="2"
                                   max="500" required/>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="MedicineApplied" name="MedicineApplied">
                            <label class="form-check-label" for="MedicineApplied">
                                Dodany lek do posiłku
                            </label>
                        </div>
                        <button class="btn btn-primary mt-3">Rozpocznij</button>
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
                            <form action="../UseCases/EndMealController.php" method="post">
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
                    <form action="../UseCases/AddPoopController.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Kupa</button>
                        </div>
                    </form>
                    <form action="../UseCases/AddPeeController.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Siku</button>
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
                    <span class="<?= $lastPoop['warning'] == 1 ? 'text-danger' : 'text-info' ?>"><?= $lastPoop['timestamp'] ?></span><br/>
                    Ostatnie siku: <span
                            class="<?= $lastPee['warning'] == 1 ? 'text-danger' : 'text-info' ?>"><?= $lastPee['timestamp'] ?></span><br/>
                    Zapotrzebowanie dzisiaj: <?= showInt($dailyDemand['total']); ?> %<br/>
                    Zapotrzebowanie wczoraj: <?= showInt($yesterdayDemand['total']); ?> %<br/>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               disabled <?= $medicineApplied ? 'checked' : '' ?> id="MedicineAppliedStatus">
                        <label class="form-check-label" for="MedicineAppliedStatus">
                            Lek podany
                        </label>
                    </div>
                    <br/>
                    <?php
                    if ($lastWeight) {
                        $lastWeightDate = $lastWeight['date'];
                        $lastWeightValue = showDecimal($lastWeight['weight'], 1) . ' kg';
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

    <div class="card mb-3">
        <div class="card-header">Obserwacja</div>
        <div class="card-body">
            <form action="../UseCases/AddObservationController.php" method="post">
                <input type="hidden" name="CatId" value="<?= $catId ?>">
                <div class="form-group">
                    <label for="ObservationNotes">Opis</label>
                    <textarea id="ObservationNotes" name="notes" class="form-control" required minlength="5"
                              maxlength="250"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Popraw pokarm</div>
                <div class="card-body">
                    <form action="../UseCases/CorrectFoodController.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <div class="form-group">
                            <label for="FoodId">Pokarm</label>
                            <select class="form-control" id="FoodId" name="FoodId"
                                    data-bind="options: foods, optionsText: 'name', optionsValue: 'id', value: foodId"> </select>
                        </div>
                        <div class="form-group">
                            <label for="FoodName">Nowa nazwa</label>
                            <input class="form-control" id="FoodName" name="FoodName" data-bind="value: foodName"
                                   required minlength="2" maxlength="30"/>
                        </div>
                        <div class="form-group">
                            <label for="FoodDescription">Nowy opis</label>
                            <textarea id="FoodDescription" name="FoodDescription" class="form-control"
                                      data-bind="value: foodDescription" minlength="5" maxlength="1000"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Weight">Zapotrzebowanie dzienne [g]</label>
                            <input id="Weight" name="Weight" class="form-control" type="number" step="1" min="30"
                                   max="500"
                                   data-bind="value: weight" required/>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="Visible" name="Visible"
                                       data-bind="checked: visible">
                                <label class="form-check-label" for="Visible">
                                    Widoczny na liście wyboru
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Zapisz</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Dodaj pokarm</div>
                <div class="card-body">
                    <form action="../UseCases/AddFoodController.php" method="post">
                        <input type="hidden" name="CatId" value="<?= $catId ?>">
                        <div class="form-group">
                            <label for="NewFoodName">Nazwa</label>
                            <input class="form-control" id="NewFoodName" name="FoodName" required minlength="2"
                                   maxlength="30"/>
                        </div>
                        <div class="form-group">
                            <label for="NewFoodDescription">Opis</label>
                            <textarea id="NewFoodDescription" name="FoodDescription" class="form-control" minlength="5"
                                      maxlength="1000"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="NewWeight">Zapotrzebowanie dzienne [g]</label>
                            <input id="NewWeight" name="Weight" class="form-control" type="number" step="1" min="30"
                                   max="500" required/>
                        </div>
                        <button type="submit" class="btn btn-primary">Zapisz</button>
                    </form>
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

                <a href="#" class="list-group-item list-group-item-action">
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

    <script src="../../Shared/Views/knockout-min.js"></script>
    <script>
        function ViewModel() {
            var me = this;
            me.startMealfoodId = ko.observable(null);
            me.foodName = ko.observable(null);
            me.foodDescription = ko.observable(null);
            me.weight = ko.observable(null);
            me.visible = ko.observable(null);
            me.foods = <?= json_encode($foods);  ?>;
            me.foodId = ko.observable(null);
            me.foodId.subscribe(function () {
                var selectedFood = ko.utils.arrayFirst(me.foods, function (food) {
                    if (food.id === me.foodId()) {
                        return food;
                    }
                });

                if (selectedFood == null) {
                    return;
                }

                me.foodName(selectedFood.name);
                me.foodDescription(selectedFood.description);
                me.weight(selectedFood.weight);
                me.visible(selectedFood.visible === '1');
            });
            me.visibleFoods = ko.utils.arrayFilter(me.foods, function (food) {
                return food.visible === '1';
            });
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel);
    </script>
<?php
include '../../Shared/Views/Footer.php';