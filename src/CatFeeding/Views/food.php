<?php
include "../../Shared/Views/View.php";
$catId = intval($_REQUEST['Id']);
$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);

if ($cat === false) {
    showFinalWarning('Nie znaleziono wybranego kota.');
}

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

$foodId = $_REQUEST['FoodId'] ? intval($_REQUEST['FoodId']) : 'null';
?>
<h1>Pokarm
    <?= $catName ?>
</h1>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Popraw pokarm</div>
            <div class="card-body">
                <form action="../UseCases/CorrectFoodUseCase.php" method="post">
                    <input type="hidden" name="CatId" value="<?= $catId ?>">
                    <div class="form-group">
                        <label for="FoodId">Pokarm</label>
                        <select class="form-control" id="FoodId" name="FoodId"
                            data-bind="options: foods, optionsText: 'name', optionsValue: 'id', value: foodId">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="FoodName">Nowa nazwa</label>
                        <input class="form-control" id="FoodName" name="FoodName" data-bind="textInput: foodName" required
                            minlength="2" maxlength="30" aria-describedby="FoodNameHelp" />
                        <small id="FoodNameHelp" class="form-text text-muted">Nabardziej podobna nazwa <a data-bind="attr: { href: similarFoodUrl }, text: similarFoodName"></a></small>
                    </div>
                    <div class="form-group">
                        <label for="FoodDescription">Nowy opis</label>
                        <textarea id="FoodDescription" name="FoodDescription" class="form-control"
                            data-bind="value: foodDescription" minlength="5" maxlength="1000"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="Weight">Zapotrzebowanie dzienne [g]</label>
                        <input id="Weight" name="Weight" class="form-control" type="number" step="1" min="30" max="500"
                            data-bind="value: weight" required />
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
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Zapisz</button>
                    </div>
                </form>
                <form action="../UseCases/HideUnusedFoods.php" method="post">
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary">Ukryj nieużywane
                        </button>
                    </div>
                </form>
                <form action="../UseCases/StartMealUseCase.php" method="post" class="form-inline">
                    <div class="form-group">
                        <button class="btn btn-primary mr-2">Rozpocznij posiłek</button>
                    </div>
                    <input type="hidden" name="CatId" value="<?= $catId ?>">
                    <input type="hidden" name="FoodId" data-bind="value: foodId">
                    <div class="form-group">
                        <label for="MealWeight" class="mr-2">Waga przed posiłkiem [g]</label>
                        <input id="MealWeight" name="Weight" class="form-control" type="number" step="1" min="1"
                            max="500" required />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Dodaj pokarm</div>
            <div class="card-body">
                <form action="../UseCases/AddFoodUseCase.php" method="post">
                    <input type="hidden" name="CatId" value="<?= $catId ?>">
                    <div class="form-group">
                        <label for="NewFoodName">Nazwa</label>
                        <input class="form-control" id="NewFoodName" name="FoodName" required minlength="2"
                            maxlength="30" aria-describedby="NewFoodNameHelp" data-bind="textInput: newFoodName" />
                        <small id="NewFoodNameHelp" class="form-text text-muted">Nabardziej podobna nazwa <a data-bind="attr: { href: similarNewFoodUrl }, text: similarNewFoodName"></a></small>
                    </div>
                    <div class="form-group">
                        <label for="NewFoodDescription">Opis</label>
                        <textarea id="NewFoodDescription" name="FoodDescription" class="form-control" minlength="5"
                            maxlength="1000"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="NewWeight">Zapotrzebowanie dzienne [g]</label>
                        <input id="NewWeight" name="Weight" class="form-control" type="number" step="1" min="30"
                            max="500" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= baseUrl() ?>/src/Shared/Views/string-similarity.min.js"></script>
<script>
    function ViewModel() {
        const me = this;
        me.foodName = ko.observable('');
        me.foods = <?= json_encode($foods) ?>;
        me.newFoodName = ko.observable('');
        me.allNames = me.foods.map(x => x.name);
        me.similarFoodName = ko.computed(() => {
            const result = stringSimilarity.findBestMatch(me.foodName(), me.allNames);
            return result.bestMatch.target;
        });
        me.similarFoodId = ko.computed(() => {
            return me.foods.find(x => x.name === me.similarFoodName()).id;
        });
        me.similarFoodUrl = ko.computed(() => {
            return `food.php?Id=<?= $catId ?>&FoodId=${me.similarFoodId()}`;
        });
        me.similarNewFoodName = ko.computed(() => {
            const result = stringSimilarity.findBestMatch(me.newFoodName(), me.allNames);
            return result.bestMatch.target;
        });
        me.similarNewFoodId = ko.computed(() => {
            return me.foods.find(x => x.name === me.similarNewFoodName()).id;
        });
        me.similarNewFoodUrl = ko.computed(() => {
            return `food.php?Id=<?= $catId ?>&FoodId=${me.similarNewFoodId()}`;
        });
        me.foodDescription = ko.observable(null);
        me.weight = ko.observable(null);
        me.visible = ko.observable(null);
        me.foodId = ko.observable(null);
        me.foodId.subscribe(function () {
            const selectedFood = me.foods.find(food => food.id === me.foodId());

            if (selectedFood == null) {
                return;
            }

            me.foodName(selectedFood.name);
            me.foodDescription(selectedFood.description);
            me.weight(parseInt(selectedFood.weight));
            me.visible(Boolean(parseInt(selectedFood.visible)));
        });

        me.foodId(<?= $foodId ?>);
    }

    const viewModel = new ViewModel();
    ko.applyBindings(viewModel);
</script>
<?php
include '../../Shared/Views/Footer.php';