<?php
include '../../Shared/Views/View.php';

$coffee = get('select c.last_cleaned, c.last_degreased, c.last_lubricated from coffee_machine c', []);

if ($coffee === false) {
    showFinalWarning('Dodaj rekord rejestru kaw.');
}

$lastCleaned = $coffee['last_cleaned'];
$lastDegreased = $coffee['last_degreased'];
$lastLubricated = $coffee['last_lubricated'];

$nextCleaning = showDate(strtotime($lastCleaned . ' + 1 week'));
$cleanVisible = today() >= $nextCleaning;

$nextDegreasing = showDate(strtotime($lastDegreased . ' + 1 month'));
$degreaseVisible = today() >= $nextDegreasing;

$nextLubricating = showDate(strtotime($lastLubricated . ' + 4 months'));
$lubricateVisible = today() >= $nextLubricating;
?>

<h1>Ekspres EP5441/50</h1>

<h2>Czyszczenie i konserwacja</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Czynność</th>
            <th>Ostatnia</th>
            <th>Następna</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Mycie ekspresu, płukanie bloku zaparzającego</td>
            <td>
                <?= $lastCleaned ?>
            </td>
            <td>
                <?= $nextCleaning ?>
            </td>
        </tr>
        <tr>
            <td>Usuwanie oleju kawowego</td>
            <td>
                <?= $lastDegreased ?>
            </td>
            <td>
                <?= $nextDegreasing ?>
            </td>
        </tr>
        <tr>
            <td>Smarowanie bloku zaparzającego</td>
            <td>
                <?= $lastLubricated ?>
            </td>
            <td>
                <?= $nextLubricating ?>
            </td>
        </tr>
    </tbody>
</table>

<form action="../UseCases/CleanUpUseCase.php" method="post">
    <input type="hidden" name="EventName" value="cleaned">
    <div class="form-group">
        <button class="btn btn-primary" type="submit" <?= $cleanVisible ? '' : 'disabled' ?>>Umyj ekspres, płucz blok
            zaparzający</button>
    </div>
</form>

<form action="../UseCases/CleanUpUseCase.php" method="post">
    <input type="hidden" name="EventName" value="degreased">
    <div class="form-group">
        <button class="btn btn-primary" type="submit" <?= $degreaseVisible ? '' : 'disabled' ?>>Usuń olej kawowy</button>
    </div>
</form>

<form action="../UseCases/CleanUpUseCase.php" method="post">
    <input type="hidden" name="EventName" value="lubricated">
    <div class="form-group">
        <button class="btn btn-primary" type="submit" <?= $lubricateVisible ? '' : 'disabled' ?>>Smaruj blok
            zaparzający</button>
    </div>
</form>

<?php
include '../../Shared/Views/Footer.php';