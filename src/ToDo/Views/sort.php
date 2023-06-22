<?php
include '../../Shared/Views/View.php';

$listName = $_GET['name'];
$toDoList = get("select s.json from to_do_list s where s.name = ?", [$listName]);
?>
<h1>Zadania -
    <?= $listName ?>
</h1>
<div class="card mb-3">
    <div class="card-header">Sortowanie</div>
    <div class="card-body">

        <div class="form-group mb-3">
            <form action="../UseCases/SaveToDoListUseCase.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="Name" value="<?= $listName ?>" />
                    <input type="hidden" name="ToDoList" data-bind="value: jsonToDoList" />
                    <button class="btn-primary btn mb-1">Zapisz</button>
                </div>
            </form>
        </div>

        <div class="list-group" data-bind="sortable: tasks">
            <div class="list-group-item">
                <div data-bind="html: formatted"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const remarkable = new Remarkable();

    function Task(text) {
        const me = this;

        me.text = text;

        me.formatted = ko.computed(() => remarkable.render(text));
    }

    const tasksSource = JSON.parse(<?= json_encode($toDoList['json']) ?>);
    const initialTasks = tasksSource.map(task => new Task(task.text));

    function ViewModel(initialTasks) {
        const me = this;

        me.tasks = ko.observableArray(initialTasks);

        me.jsonToDoList = ko.computed(function () {
            const tasks = ko.toJS(me.tasks);
            const tasksData = tasks.map(task => ({ text: task.text, urgent: task.urgent, important: task.important }));
            return ko.toJSON(tasksData);
        });
    }

    const viewModel = new ViewModel(initialTasks);
    ko.applyBindings(viewModel);
</script>

<?php
include '../../Shared/Views/Footer.php';
?>