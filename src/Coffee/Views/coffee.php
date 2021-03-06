<?php
include '../../Shared/Views/View.php';

$coffees = get('select c.current, c.last_cleaning from coffees c', []);

if ($coffees === false) {
    showFinalWarning('Dodaj rekord rejestru kaw.');
}

$coffeesUntilCleaning = $coffees['last_cleaning'] + 100 - $coffees['current'];

if ($coffeesUntilCleaning <= 0) {
    showWarning('Umyj ekspres.');
} else {
    showInfo("Jeszcze $coffeesUntilCleaning kaw do mycia.");
}

?>

    <h1>Kawa</h1>

    <form action="../UseCases/MakeCoffeeUseCase.php" method="post">
        <input type="hidden" name="CoffesCount" value="1">
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Zrób kawę</button>
        </div>
    </form>
    <form action="../UseCases/MakeCoffeeUseCase.php" method="post">
        <input type="hidden" name="CoffesCount" value="2">
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Zrób dwie kawy</button>
        </div>
    </form>
    <form action="../UseCases/CleanUpUseCase.php" method="post">
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Umyj ekspres</button>
        </div>
    </form>

    <p>Kawa przygotowana <?= showInt($coffees['current']) ?> razy.</p>
<?php
include '../../Shared/Views/Footer.php';
