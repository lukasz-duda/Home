<?php
include '../../Shared/Views/View.php';

$listName = $_GET['name'];
$toDoList = get("select s.json from to_do_list s where s.name = ?", [$listName]);
?>
    <h1>Zadania</h1>
    <div class="card mb-3">
        <div class="card-header" data-bind="click: unselect">Planowanie</div>
        <div class="card-body">

            <button class="btn-primary btn mb-3" data-bind="click: addTask">Dodaj</button>

            <div class="list-group" data-bind="sortable: tasks">
                <div class="list-group-item">
                    <div data-bind="visible: !$root.isSelectedTask($data), html: formatted, click: $root.selectedTask"></div>
                    <div class="form-group m-0" data-bind="visibleAndSelected: $root.isSelectedTask($data)">
                        <div class="input-group">
                    <textarea class="form-control" rows="3" minlength="2" maxlength="4000" required
                              data-bind="value: text"></textarea>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" data-bind="click: $parent.remove">Usuń
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <form action="../Application/SaveToDoListController.php" method="post">
                    <div class="form-group">
                        <input type="hidden" name="Name" value="<?= $listName ?>"/>
                        <input type="hidden" name="ToDoList" data-bind="value: jsonToDoList"/>
                        <button class="btn-primary btn">Zapisz</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var remarkable = new Remarkable();

        function Task(markdown) {
            var me = this;

            me.text = ko.observable(markdown);

            me.formatted = ko.computed(function () {
                return remarkable.render(me.text());
            });
        }

        function ViewModel() {
            var me = this;
            me.initialTasksData = JSON.parse(<?= json_encode($toDoList['json']);?>);
            me.initialTasks = jQuery.map(me.initialTasksData, function (task) {
                return new Task(task.text);
            });
            me.tasks = ko.observableArray(me.initialTasks);

            me.addTask = function () {
                me.tasks.push(new Task('Nowe zadanie'));
            };

            me.selectedTask = ko.observable(null);

            me.unselect = function () {
                me.selectedTask(null);
            };

            me.remove = function (data) {
                me.tasks.remove(data);
            };

            me.isSelectedTask = function (task) {
                return task === me.selectedTask();
            };

            me.jsonToDoList = ko.computed(function () {
                var tasks = ko.toJS(me.tasks);
                var tasksData = jQuery.map(tasks, function (task) {
                    return {text: task.text};
                });
                return ko.toJSON(tasksData);
            });
        }

        var viewModel = new ViewModel();

        ko.bindingHandlers.visibleAndSelected = {
            update: function (element, valueAccessor) {
                ko.bindingHandlers.visible.update(element, valueAccessor);
                if (valueAccessor()) {
                    setTimeout(function () {
                        $(element).find("textarea").focus().select();
                    }, 0);
                }
            }
        };

        ko.applyBindings(viewModel);
    </script>

<?php
include '../../Shared/Views/Footer.php';
?>