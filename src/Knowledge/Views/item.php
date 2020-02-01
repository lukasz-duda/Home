<?php
include '../../Shared/Views/View.php';
$itemId = $_REQUEST['Id'];
$item = get('select i.* from knowledge_items i where i.id = ?', [$itemId]);
?>

    <h1 data-bind="visible: !editingHeader(), text: header, click: editHeader"></h1>
    <form method="post" action="../Application/SaveKnowledgeItem.php">
        <div class="form-group" data-bind="visible: editingHeader">
            <label for="Header">Nagłówek</label>
            <input name="Header" id="Header" class="form-control" data-bind="value: header, hasFocus: editingHeader"/>
        </div>

        <div data-bind="visible: !editingContent(), html: contentFormatted, click: editContent"></div>
        <div class="form-group m-0" data-bind="visible: editingContent">
        <textarea class="form-control" rows="15" minlength="2" maxlength="4000"
                  name="Content" placeholder="Treść" required
                  data-bind="value: content, hasFocus: editingContent"></textarea>
        </div>

        <div class="form-group">
            <label for="Keywords">Słowa kluczowe</label>
            <input name="Keywords" id="Keywords" class="form-control" value="<?= $item['keywords'] ?>"/>
        </div>

        <input type="hidden" name="Id" value="<?= $itemId ?>"/>
        <button class="btn btn-primary mt-3">Zapisz</button>
    </form>
    <form method="post" action="../Application/DeleteKnowledgeItem.php">
        <input type="hidden" name="Id" value="<?= $itemId ?>"/>
        <button class="btn btn-secondary mt-3">Usuń</button>
    </form>

    <script>
        var remarkable = new Remarkable();

        function ViewModel() {
            var me = this;

            me.item = <?= json_encode($item) ?>;

            me.content = ko.observable(me.item.content);

            me.header = ko.observable(me.item.header);

            me.editingHeader = ko.observable(false);

            me.editHeader = function () {
                me.editingHeader(true);
            };

            me.contentFormatted = ko.computed(function () {
                return remarkable.render(me.content());
            });

            me.editingContent = ko.observable(false);

            me.editContent = function () {
                me.editingContent(true);
            }
        }

        var viewModel = new ViewModel();
        ko.applyBindings(viewModel);
    </script>

<?php
include '../../Shared/Views/Footer.php';