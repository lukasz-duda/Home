<?php
include '../../Shared/Views/View.php';
$items = getAll('select i.id, i.header, i.keywords from knowledge_items i order by i.header', []);
?>

    <h1>Baza wiedzy</h1>

    <div class="form-group">
        <a href="item.php" class="btn btn-primary">Dodaj</a>
    </div>
    <div class="form-group">
        <label for="Search">Szukaj w bazie wiedzy</label>
        <div class="input-group mb-3">
            <input type="search" class="form-control" id="Search" data-bind="value: query, hasFocus: true"
                   aria-describedby="Searchicon">
            <div class="input-group-append">
                <span class="input-group-text" id="SearchIcon"><i class="material-icons-outlined"
                                                                  style="font-size: inherit">search</i></span>
            </div>
        </div>
    </div>

    <ul class="list-group" data-bind="foreach: matchingItems">
        <li class="list-group-item list-group-item-action" data-bind="text: header, click: $parent.showItem"></li>
    </ul>

    <script>
        function ViewModel() {
            var me = this;

            me.items = ko.observableArray(<?= json_encode($items) ?>);

            me.query = ko.observable('<?= $_REQUEST['query'] ?>');

            me.matchingItems = ko.computed(function () {
                return jQuery.grep(me.items(), function (item) {
                    return item.header.toLowerCase().indexOf(me.query().toLowerCase()) > -1
                        || item.keywords.toLowerCase().indexOf(me.query().toLowerCase()) > -1;
                });
            });

            me.showItem = function (item) {
                window.location = 'item.php?Id=' + item.id;
            }
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel);
    </script>

<?php
include '../../Shared/Views/Footer.php';
