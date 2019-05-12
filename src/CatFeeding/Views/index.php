<?php
include "../../Shared/Views/View.php";
$catId = 1;
$cat = get('SELECT name FROM cats WHERE id = ?', [$catId]);
$catName = $cat['name'];
$foods = getAll('SELECT f.id, f.name, f.description,
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
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $catName ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="container">
<h1><?= $catName ?></h1>
<h2>Dodaj pokarm</h2>
<form action="../Application/AddFoodController.php" method="post">
    <div class="form-group">
        <label for="FoodName">Nazwa</label>
        <input class="form-control" id="FoodName" name="FoodName"/>
    </div>
    <div class="form-group">
        <label for="FoodDescription">Opis</label>
        <textarea id="FoodDescription" name="FoodDescription" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label for="Weight">Zapotrzebowanie dzienne [g]</label>
        <input id="Weight" name="Weight" class="form-control" type="number" step="1" min="0" max="1000"/>
    </div>
    <button type="submit" class="btn btn-primary">Zapisz</button>
</form>
<h2>Popraw pokarm</h2>
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
    <button type="submit" class="btn btn-primary">Zapisz</button>
</form>
<h2>Podsumowanie dnia</h2>
<h3>Rozpocznij posiłek</h3>
<h3>Zakończ posiłek</h3>
<h3>Kupa</h3>
<h3>Siku</h3>
<h3>Obserwuj</h3>
<script src="../../Shared/Views/knockout-min.js"></script>
<script>
    function ViewModel() {
        var me = this;
        me.foodName = ko.observable(null);
        me.foodDescription = ko.observable(null);
        me.weight = ko.observable(null);
        me.foods = <?= json_encode($foods);  ?>;
        me.foodId = ko.observable(null);
        me.foodId.subscribe(function () {
            var selectedFood = ko.utils.arrayFirst(me.foods, function (food) {
                if (food.id === me.foodId()) {
                    return food;
                }
            });
            me.foodName(selectedFood.name);
            me.foodDescription(selectedFood.description);
            me.weight(selectedFood.weight);
        });
    }

    var viewModel = new ViewModel();
    ko.applyBindings(viewModel);
</script>
</body>
</html>