<?php
include '../../Shared/Views/View.php';
$itemId = $_REQUEST['Id'];
$newItem = $itemId == null;
$item = $newItem ?
    array('header' => '', 'keywords' => '', 'content' => '') :
    get('select i.header, i.keywords, i.content from knowledge_items i where i.id = ?', [$itemId]);
?>

<h1 data-bind="visible: !editing(), text: header"></h1>
<form method="post" action="../UseCases/SaveKnowledgeItemUseCase.php">
    <div class="form-group" data-bind="visible: editing">
        <label for="Header">Nagłówek</label>
        <input name="Header" id="Header" class="form-control" minlength="2" required maxlength="250"
            data-bind="value: header" />
    </div>

    <div data-bind="visible: !editing(), html: contentFormatted"></div>
    <div class="form-group" data-bind="visible: editing">
        <label for="Content">Treść</label>
        <textarea class="form-control" rows="15" minlength="2" maxlength="4000" name="Content" required id="Content"
            data-bind="value: content"></textarea>
    </div>

    <p data-bind="visible: !editing()">Słowa kluczowe: <span data-bind="text: keywords"></span></p>
    <div class="form-group" data-bind="visible: editing">
        <label for="Keywords">Słowa kluczowe</label>
        <input name="Keywords" id="Keywords" class="form-control" minlength="2" required maxlength="250"
            data-bind="value: keywords" />
    </div>

    <input type="hidden" name="Id" value="<?= $itemId ?>" />
    <div class="form-group">
        <button class="btn btn-primary">Zapisz</button>
        <button class="btn btn-secondary" data-bind="visible: !editing(), click: toggleEdit">Edytuj</button>
        <button class="btn btn-secondary" data-bind="visible: editing, click: toggleEdit">Podgląd</button>
    </div>
</form>
<form method="post" action="../UseCases/DeleteKnowledgeItemUseCase.php">
    <div class="form-group">
        <input type="hidden" name="Id" value="<?= $itemId ?>" />
        <button class="btn btn-secondary">Usuń</button>
    </div>
</form>

<script>
    const remarkable = new Remarkable();

    const initialItem = <?= json_encode($item) ?>;

    function ViewModel(sourceItem) {
        const me = this;

        me.content = ko.observable(sourceItem.content);

        me.header = ko.observable(sourceItem.header);

        me.keywords = ko.observable(sourceItem.keywords);

        me.toggleEdit = function () {
            me.editing(!me.editing());
        };

        me.editing = ko.observable(<?= $newItem ?>);

        me.contentFormatted = ko.computed(function () {
            return remarkable.render(me.content());
        });
    }

    const viewModel = new ViewModel(initialItem);
    ko.applyBindings(viewModel);
</script>

<?php
include '../../Shared/Views/Footer.php';