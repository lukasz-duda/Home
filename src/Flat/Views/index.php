<?php
include "../../Shared/Views/View.php";

$ilona = get('select sum(e.value) as value from flat_expense e where e.person = ?', ['Ilona']);
$lukasz = get('select sum(e.value) as value from flat_expense e where e.person = ?', ['Łukasz']);
$last = getAll('select timestamp, person, name, value from flat_expense', []);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mieszkanie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="container">
<h1>Mieszkanie</h1>
<h2>Podsumowanie</h2>
Łukasz zapłacił: <?= $lukasz['value'] ?><br/>
Ilona zapłaciła: <?= $ilona['value'] ?><br/>
Ilona zwróci jeszcze: <?= $lukasz['value'] - $ilona['value'] ?>
<h2>Nowa wpłata</h2>
<form action="../Application/AddFlatExpenseController.php" method="post">
    <div class="form-group">
        <label for="Value">Wartość</label>
        <input class="form-control" id="Value" name="Value" required type="number" step="0.01"
               data-bind="value: value"/>
    </div>
    <div class="form-group">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="Person" id="Ilona" value="Ilona">
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
            <input class="form-control" id="Name" name="Name" required/>
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-primary">Dodaj</button>
    </div>
</form>
</body>
</html>