<?php
include '../../Shared/Views/View.php';

$shoppingList = get('select s.json from shopping_list s', []);
?>
<h1>Planowanie zakupów</h1>

<div class="card mb-3">
    <div class="card-header">Planowanie</div>
    <div class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action" href="reports.php">Raporty zakupów</a>
    </div>
    <div class="card-body">
        <button class="btn-primary btn mb-3" data-bind="click: addShoppingItem">Dodaj</button>
        <div data-bind="foreach: items">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Nazwa pozycji listy zakupów" required
                        minlength="2" maxlength="250" data-bind="value: name">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" data-bind="click: $parent.remove">Usuń</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <form action="../UseCases/SaveShoppingListUseCase.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="ShoppingList" data-bind="value: jsonShoppingList" />
                    <button class="btn btn-primary">Zapisz</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const previous = ko.mapping.fromJSON(<?= json_encode($shoppingList['json']); ?>);
    
    function ShoppingList(previousVersion, items) {
        const me = this;
        me.previousVersion = previousVersion ?? crypto.randomUUID();
        me.items = items;
    }

    function ViewModel() {
        const me = this;

        me.items = ko.observableArray(previous.items());

        me.addShoppingItem = function () {
            me.items([{ name: ko.observable(null) }, ...me.items()]);
        };

        me.remove = function (item) {
            me.items.remove(item);
        };

        me.jsonShoppingList = ko.computed(() => ko.toJSON(new ShoppingList(previous.previousVersion, me.items())));
    }

    const viewModel = new ViewModel();
    ko.applyBindings(viewModel);
</script>
<?php
include '../../Shared/Views/Footer.php';