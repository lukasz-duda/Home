<?php
include "../../Shared/Views/View.php";
$catId = 1;
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

$lastMeals = getAll('select m.id, f.name, m.start, m.start_weight, m.end, m.end_weight
from meal m
    join food f on f.id = m.food_id
where m.end is not null
  and m.cat_id = ?
order by m.start desc 
  limit 10', [$catId]);
$now = time();
$lastPoop = get('select p.timestamp
from poop p
where p.cat_id = ?
order by p.timestamp desc
limit 1', [$catId]);
$lastPee = get('select p.timestamp
from pee p
where p.cat_id = ?
order by p.timestamp desc
limit 1', [$catId]);
$dailyDemand = get('select
100 * sum(round(m.start_weight - m.end_weight))
/
(
   select d.weight
   from daily_demand d
   where d.food_id = m.food_id
	 and d.cat_id = ?
   order by d.timestamp desc limit 1
) as total
from meal m
where m.cat_id = ?
and datediff(m.start, ?) = 0
group by m.cat_id', [$catId, $catId, date('Y-m-d', $now)]);
$yesterdayDemand = get('select
100 * sum(round(m.start_weight - m.end_weight))
/
(
   select d.weight
   from daily_demand d
   where d.food_id = m.food_id
	 and d.cat_id = ?
   order by d.timestamp desc limit 1
) as total
from meal m
where m.cat_id = ?
and datediff(m.start, ?) = 0
group by m.cat_id', [$catId, $catId, date('Y-m-d', strtotime('-1 days'))]);
?>
    <h1><?= $catName ?></h1>

    <div class="card mb-3">
        <div class="card-header">Podsumowanie dnia</div>
        <div class="card-body">
            Teraz: <?= date('Y-m-d H:i:s', $now); ?><br/>
            Ostatnia kupa: <?= $lastPoop['timestamp'] ?><br/>
            Ostatnie siku: <?= $lastPee['timestamp'] ?><br/>
            Zapotrzebowanie dzisiaj: <?= showInt($dailyDemand['total']); ?> %<br/>
            Zapotrzebowanie wczoraj: <?= showInt($yesterdayDemand['total']); ?> %<br/>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Rozpocznij posiłek</div>
        <div class="card-body">
            <form action="../Application/StartMealController.php" method="post">
                <div class="form-group">
                    <label for="MealFoodId">Pokarm</label>
                    <select class="form-control" id="MealFoodId" name="FoodId"
                            data-bind="options: visibleFoods, optionsText: 'name', optionsValue: 'id', value: startMealfoodId"> </select>
                </div>
                <div class="form-group">
                    <label for="MealWeight">Waga przed posiłkiem [g]</label>
                    <input id="MealWeight" name="Weight" class="form-control" type="number" step="1" min="0"
                           max="1000"/>
                </div>
                <button class="btn btn-primary">Rozpocznij</button>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Zakończ posiłek</div>
        <div class="card-body">
            <div class="list-group">
                <?php foreach ($meals as $meal) {
                    ?>
                    <form action="../Application/EndMealController.php" method="post">
                        <input type="hidden" name="MealId" value="<?= $meal['id'] ?>"/>
                        <div class="form-group">
                            <label for="Meal<?= $meal['id'] ?>Weight">Waga <?= $meal['name'] ?> po
                                posiłku <?= $meal['start_weight'] ?> g
                                rozpoczętym <?= $meal['start'] ?> [g]</label>
                            <input id="Meal<?= $meal['id'] ?>Weight" name="Weight" class="form-control" type="number"
                                   step="1"
                                   min="0" max="1000"/>
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

    <div class="card mb-3">
        <div class="card-header">Toaleta</div>
        <div class="card-body">
            <form action="../Application/AddPoopController.php" method="post">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Kupa</button>
                </div>
            </form>
            <form action="../Application/AddPeeController.php" method="post">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Siku</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Obserwacja</div>
        <div class="card-body">
            <form action="../Application/AddObservationController.php" method="post">
                <div class="form-group">
                    <label for="ObservationNotes">Opis</label>
                    <textarea id="ObservationNotes" name="notes" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Dodaj pokarm</div>
        <div class="card-body">
            <form action="../Application/AddFoodController.php" method="post">
                <div class="form-group">
                    <label for="NewFoodName">Nazwa</label>
                    <input class="form-control" id="NewFoodName" name="FoodName"/>
                </div>
                <div class="form-group">
                    <label for="NewFoodDescription">Opis</label>
                    <textarea id="NewFoodDescription" name="FoodDescription" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="NewWeight">Zapotrzebowanie dzienne [g]</label>
                    <input id="NewWeight" name="Weight" class="form-control" type="number" step="1" min="0" max="1000"/>
                </div>
                <button type="submit" class="btn btn-primary">Zapisz</button>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Popraw pokarm</div>
        <div class="card-body">
            <form action="../Application/CorrectFoodController.php" method="post">
                <div class="form-group">
                    <label for="FoodId">Pokarm</label>
                    <select class="form-control" id="FoodId" name="FoodId"
                            data-bind="options: foods, optionsText: 'name', optionsValue: 'id', value: foodId"> </select>
                </div>
                <div class="form-group">
                    <label for="FoodName">Nowa nazwa</label>
                    <input class="form-control" id="FoodName" name="FoodName" data-bind="value: foodName"/>
                </div>
                <div class="form-group">
                    <label for="FoodDescription">Nowy opis</label>
                    <textarea id="FoodDescription" name="FoodDescription" class="form-control"
                              data-bind="value: foodDescription"></textarea>
                </div>
                <div class="form-group">
                    <label for="Weight">Zapotrzebowanie dzienne [g]</label>
                    <input id="Weight" name="Weight" class="form-control" type="number" step="1" min="0" max="1000"
                           data-bind="value: weight"/>
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

    <div class="card mb-3">
        <div class="card-header">Ostatnie posiłki</div>
        <div class="card-body">
            <?php
            foreach ($lastMeals as $lastMeal) {
                ?>

                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= $lastMeal['name'] ?></h5>
                    </div>
                    <p class="mb-1"><?= $lastMeal['start_weight'] ?> - <?= $lastMeal['end_weight'] ?>
                        = <?= showInt($lastMeal['start_weight'] - $lastMeal['end_weight']) ?> g</p>
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