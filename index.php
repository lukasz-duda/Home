<?php
include __DIR__ . '/src/Shared/Views/View.php';

$currentMonthStartDate = date('Y-m', time()) . '-01T00:00:00';
$currentMonthEndDate = date("Y-m-t", strtotime($currentMonthStartDate)) . 'T23:59:59';

$previousMonthStartDate = date('Y-m-d', strtotime(date('Y-m') . " -1 month")) . 'T00:00:00';
$previousMonthEndDate = date("Y-m-t", strtotime($previousMonthStartDate)) . 'T23:59:59'
?>

    <h1>Dom</h1>

    <div class="form-group">
        <label for="Search">Szukaj w menu</label>
        <div class="input-group mb-3">
            <input type="search" class="form-control" id="Search" data-bind="value: query, hasFocus: true"
                   aria-describedby="SearchIcon">
            <div class="input-group-append">
                <span class="input-group-text" id="SearchIcon"><i class="material-icons-outlined"
                                                                  style="font-size: inherit">search</i></span>
            </div>
        </div>
    </div>

    <ul class="list-group mb-3" data-bind="foreach: matchingItems">
        <li class="list-group-item list-group-item-action" data-bind="text: text, click: $parent.showItem"></li>
    </ul>

    <script>
        function MenuItem(text, href) {
            const me = this;
            me.text = text;
            me.href = href;
            me.isShortcut = false;
        }

        function Shortcut(text, href) {
            const me = this;
            me.text = text;
            me.href = href;
            me.isShortcut = true;
        }

        function ViewModel() {
            const me = this;

            me.showShortcuts = true;

            me.items = ko.observableArray([
                new Shortcut('Mgiełka', 'src/CatFeeding/Views/cat.php?Id=2'),
                new Shortcut('Planowanie zakupów', 'src/Shopping/Views/plan.php'),
                new Shortcut('Kategorie zakupów w bieżącym miesiącu', 'src/Shopping/Views/Reports/categories.php?StartDate=<?= $currentMonthStartDate ?>&EndDate=<?= $currentMonthEndDate ?>'),
                new MenuItem('Golf', 'src/CarMaintenance/Views/car.php?Id=1'),
                new Shortcut('Toyota', 'src/CarMaintenance/Views/car.php?Id=2'),
                new MenuItem('Roczne wydatki na Golfa', 'src/CarMaintenance/Views/Reports/car_annual.php?Id=1'),
                new MenuItem('Roczne wydatki na Toyotę', 'src/CarMaintenance/Views/Reports/car_annual.php?Id=2'),
                new MenuItem('Szyszka', 'src/CatFeeding/Views/cat.php?Id=1'),
                new MenuItem('Kawa', 'src/Coffee/Views/coffee.php'),
                new Shortcut('Mieszkanie', 'src/Flat/Views/flat.php'),
                new Shortcut('Baza wiedzy', 'src/Knowledge/Views/knowledge_search.php'),
                new MenuItem('Ślub', 'src/Knowledge/Views/knowledge_search.php?query=ślub'),
                new MenuItem('Dieta', 'src/Knowledge/Views/knowledge_search.php?query=dieta'),
                new MenuItem('Zakupy', 'src/Shopping/Views/expenses.php'),
                new MenuItem('Raporty zakupów', 'src/Shopping/Views/reports.php'),
                new MenuItem('Zadania - Łukasz', 'src/ToDo/Views/edit.php?name=Łukasz'),
                new MenuItem('Zadania - Ilona', 'src/ToDo/Views/edit.php?name=Ilona'),
                new MenuItem('Leki Szyszka', 'src/CatFeeding/Views/medicine.php?Id=1'),
                new MenuItem('Leki Mgiełka', 'src/CatFeeding/Views/medicine.php?Id=2'),
                new MenuItem('Pokarm Szyszka', 'src/CatFeeding/Views/food.php?Id=1'),
                new MenuItem('Pokarm Mgiełka', 'src/CatFeeding/Views/food.php?Id=2'),
                new Shortcut('Teodor', 'teodor'),
                new MenuItem('Rozliczenie zakupów', 'src/Shopping/Views/settlement.php'),
                new MenuItem('Firmy', 'src/CarMaintenance/Views/companies.php')
            ]);

            me.query = ko.observable(null);

            me.matchingItems = ko.computed(function () {
                const matchingItems = jQuery.grep(me.items(), function (item) {
                    return me.showShortcuts && item.isShortcut
                        || me.query() != null && item.text.toLowerCase().indexOf(me.query().toLowerCase()) > -1;
                });

                me.showShortcuts = false;
                return matchingItems;
            });

            me.showItem = function (item) {
                window.location = item.href;
            }
        }

        const viewModel = new ViewModel();
        ko.applyBindings(viewModel);
    </script>

<?php
include __DIR__ . '/src/Shared/Views/Footer.php';
