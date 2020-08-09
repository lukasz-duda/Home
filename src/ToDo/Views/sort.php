<?php
include '../../Shared/Views/View.php';

$listName = $_GET['name'];
$toDoList = get("select s.json from to_do_list s where s.name = ?", [$listName]);
?>
    <h1>Zadania - <?= $listName ?></h1>
    <div class="card mb-3">
        <div class="card-header">Sortowanie</div>
        <div class="card-body">

            <div class="form-group mb-3">
                <form action="../UseCases/SaveToDoListUseCase.php" method="post">
                    <div class="form-group">
                        <input type="hidden" name="Name" value="<?= $listName ?>"/>
                        <input type="hidden" name="ToDoList" data-bind="value: jsonToDoList"/>
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

        function Task(initialText) {
            const me = this;

            me.text = ko.observable(initialText);

            me.formatted = ko.computed(function () {
                return remarkable.render(me.text());
            });
        }

        function ViewModel() {
            const me = this;
            me.initialTasksData = JSON.parse(<?= json_encode($toDoList['json']);?>);
            me.initialTasks = jQuery.map(me.initialTasksData, function (task) {
                return new Task(task.text);
            });
            me.tasks = ko.observableArray(me.initialTasks);

            me.jsonToDoList = ko.computed(function () {
                const tasks = ko.toJS(me.tasks);
                const tasksData = jQuery.map(tasks, function (task) {
                    return {text: task.text};
                });
                return ko.toJSON(tasksData);
            });
        }

        const viewModel = new ViewModel();

        ko.applyBindings(viewModel);
    </script>

<?php
include '../../Shared/Views/Footer.php';
?>