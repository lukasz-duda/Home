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
            aria-describedby="SearchIcon">
        <div class="input-group-append">
            <span class="input-group-text" id="SearchIcon"><i class="material-icons-outlined"
                    style="font-size: inherit">search</i></span>
        </div>
    </div>
</div>

<ul class="list-group mb-3" data-bind="foreach: matchingItems">
    <li class="list-group-item list-group-item-action" data-bind="text: header, click: $parent.showItem"></li>
</ul>

<script>
    function ViewModel() {
        const me = this;

        me.items = <?= json_encode($items) ?>;

        me.query = ko.observable('<?= $_REQUEST['query'] ?>');

        me.matchingItems = ko.computed(function () {
            return me.items.filter(item =>
                item.header.toLowerCase().includes(me.query().toLowerCase())
                || item.keywords.toLowerCase().includes(me.query().toLowerCase()));
        });

        me.showItem = function (item) {
            window.location = 'item.php?Id=' + item.id;
        }
    }

    const viewModel = new ViewModel();
    ko.applyBindings(viewModel);
</script>

<?php
include '../../Shared/Views/Footer.php';