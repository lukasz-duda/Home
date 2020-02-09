<?php
include '../../Shared/Views/View.php';

$currentMonthStartDate = date('Y-m', time()) . '-01T00:00:00';
$currentMonthEndDate = date("Y-m-t", strtotime($currentMonthStartDate)) . 'T23:59:59';

$previousMonthStartDate = date('Y-m-d', strtotime(date('Y-m') . " -1 month")) . 'T00:00:00';
$previousMonthEndDate = date("Y-m-t", strtotime($previousMonthStartDate)) . 'T23:59:59'
?>

    <h1>Raporty</h1>

    <div class="card mb-3">
        <div class="card-header">Raporty zakupów</div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action"
               href="Reports/categories.php?StartDate=<?= $currentMonthStartDate ?>&EndDate=<?= $currentMonthEndDate ?>">Kategorie
                zakupów w bieżącym miesiącu</a>
            <a class="list-group-item list-group-item-action"
               href="Reports/categories.php?StartDate=<?= $previousMonthStartDate ?>&EndDate=<?= $previousMonthEndDate ?>">Kategorie
                zakupów w poprzednim miesiącu</a>
            <a class="list-group-item list-group-item-action"
               href="Reports/categories.php?StartDate=2000-01-01&EndDate=3000-01-01">Podsumowanie
                kategorii</a>
            <a class="list-group-item list-group-item-action"
               href="Reports/categories_annual_avg.php">Średnie miesięczne wydatki w roku według kategorii</a>
        </div>
    </div>

<?php
include '../../Shared/Views/Footer.php';