<?php
include __DIR__ . '/src/Shared/Views/View.php';
?>

    <h1>Dom</h1>
    <div class="card mb-3">
        <div class="card-header">Skróty</div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action" href="src/Shopping/Views/plan.php">Planowanie zakupów</a>
            <a class="list-group-item list-group-item-action" href="src/Knowledge/Views/index.php">Baza wiedzy</a>
            <a class="list-group-item list-group-item-action" href="src/Knowledge/Views/index.php?query=dieta">Dieta</a>
             <a class="list-group-item list-group-item-action" href="src/Flat/Views/index.php">Mieszkanie</a>
       </div>
    </div>
<?php
include __DIR__ . '/src/Shared/Views/Footer.php';
