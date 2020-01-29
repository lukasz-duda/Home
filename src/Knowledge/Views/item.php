<?php
include '../../Shared/Views/View.php';
$itemId = $_REQUEST['Id'];
$item = get('select i.* from knowledge_items i where i.id = ?', [$itemId]);
?>

    <h1 data-bind="text: header"></h1>

    <div data-bind="visible: !editingContent(), html: contentFormatted, click: editContent"></div>
    <div class="form-group m-0" data-bind="visible: editingContent">
    <textarea class="form-control" rows="20" minlength="2" maxlength="4000"
              placeholder="Treść" required
              data-bind="value: content, hasFocus: editingContent"></textarea>
    </div>

    <button class="btn btn-primary mt-3" data-bind="">Zapisz</button>
    <button class="btn btn-secondary mt-3" data-bind="">Usuń</button>

    <script>
        var remarkable = new Remarkable();

        function ViewModel() {
            var me = this;

            me.item = <?= json_encode($item) ?>;

            me.content = ko.observable(me.item.content);

            me.header = ko.observable(me.item.header);

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