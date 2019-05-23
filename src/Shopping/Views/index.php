<?php
include "../../Shared/Views/View.php";

$categories = getAll('select id, name from expense_categories', []);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Zakupy</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="container">
<h1>Zakupy</h1>
<h2>Dodaj</h2>
<form action="../Application/AddExpenseController.php" method="post">
    <div class="form-group">
        <label for="Value">Wartość zakupu</label>
        <input class="form-control" id="Value" name="Value" type="number" step="0.01" data-bind="value: value"/>
    </div>
    <div class="form-group">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="Refund" id="NoRefund" value="NoRefund" checked>
            <label class="form-check-label" for="NoRefund">
                Zwykły zakup
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="Refund" id="RefundToMe" value="RefundToMe">
            <label class="form-check-label" for="RefundToMe">
                Zwróć połowę mnie
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="Refund" id="RefundToIlona" value="RefundToIlona">
            <label class="form-check-label" for="RefundToIlona">
                Zwróć połowę Ilonie
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="form-group">
            <label for="Name">Nazwa</label>
            <input class="form-control" id="Name" name="Name"/>
        </div>
    </div>
    <div class="form-group">
        <label for="CategoryId">Firma</label>
        <select class="form-control" id="CategoryId" name="CategoryId">
            <?php
            foreach ($categories as $category) {
                ?>
                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <button class="btn btn-primary">Zapisz</button>
    </div>
</form>
</body>
</html>
