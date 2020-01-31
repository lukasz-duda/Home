<?php
include __DIR__ . '/src/Shared/Views/View.php';
?>

    <h1>Dom</h1>
    <div class="card mb-3">
        <div class="card-header">Szukaj w menu</div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action" href="src/CatFeeding/Views/index.php">Szyszka</a>
            <a class="list-group-item list-group-item-action" href="src/Shopping/Views/index.php">Zakupy</a>
            <a class="list-group-item list-group-item-action" href="src/Coffee/Views/index.php">Kawa</a>
            <a class="list-group-item list-group-item-action" href="src/Flat/Views/index.php">Mieszkanie</a>
            <a class="list-group-item list-group-item-action" href="src/CarMaintenance/Views/index.php">Golf</a>
            <a class="list-group-item list-group-item-action" href="src/ToDo/Views/edit.php?name=Łukasz">Zadania
                Łukasza</a>
            <a class="list-group-item list-group-item-action" href="src/ToDo/Views/edit.php?name=Ilona">Zadania
                Ilony</a>
            <a class="list-group-item list-group-item-action" href="src/Knowledge/Views/index.php">Baza wiedzy</a>
        </div>
    </div>
<?php
include __DIR__ . '/src/Shared/Views/Footer.php';