<?php
include '../../Shared/Views/View.php';

$listName = $_GET['name'];
$toDoList = get("select s.json from to_do_list s where s.name = ?", [$listName]);
?>
<h1>Zadania -
    <?= $listName ?>
</h1>
<div class="card mb-3">
    <div class="card-header">Edytowanie</div>
    <div class="card-body">

        <div class="form-group mb-3">
            <form action="../UseCases/SaveToDoListUseCase.php" method="post">
                <div class="form-group">
                    <button class="btn-primary btn mb-1" data-bind="click: addTask">Dodaj</button>

                    <a href="sort.php?name=<?= $listName ?>" class="btn-primary btn mb-1">Sortuj</a>

                    <a href="priorities.php?name=<?= $listName ?>" class="btn-secondary btn mb-1">Priorytety</a>

                    <input type="hidden" name="Name" value="<?= $listName ?>" />
                    <input type="hidden" name="ToDoList" data-bind="value: jsonToDoList" />
                    <button class="btn-primary btn mb-1">Zapisz</button>
                </div>
            </form>
        </div>

        <div class="list-group" data-bind="foreach: tasks">
            <div class="list-group-item" data-bind="click: edit">
                <div data-bind="visible: !editing(), html: formatted"></div>
                <div class="form-group m-0" data-bind="visible: editing">
                    <textarea class="form-control" rows="3" minlength="2" maxlength="4000" placeholder="Opis zadania"
                        required data-bind="value: text, hasFocus: editing"></textarea>
                </div>
                <button class="btn btn-outline-secondary mt-3" data-bind="click: $parent.removeTask">Usuń</button>
            </div>
        </div>
    </div>
</div>

<script>
    const remarkable = new Remarkable();

    function Task(initialText, urgent = false, important = false) {
        const me = this;

        me.text = ko.observable(initialText);

        me.urgent = urgent;

        me.important = important;

        me.formatted = ko.computed(() => remarkable.render(me.text()));

        me.editing = ko.observable(false);

        me.edit = function () {
            me.editing(true);
        }
    }

    const tasksSource = JSON.parse(<?= json_encode($toDoList['json']) ?>);
    const initialTasks = tasksSource.map(task => new Task(task.text, task.urgent, task.important));

    function ViewModel(initialTasks) {
        const me = this;
        me.tasks = ko.observableArray(initialTasks);

        me.addTask = function () {
            const newTask = new Task('');
            const current = me.tasks();
            const newTasks = [newTask, ...current];
            me.tasks(newTasks);

            newTask.edit();
        };

        me.removeTask = function (task) {
            me.tasks.remove(task);
        };

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