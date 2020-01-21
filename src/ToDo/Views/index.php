<?php
include '../../Shared/Views/View.php';

$listName = $_GET['name'];
$toDoList = get("select s.json from to_do_list s where s.name = ?", [$listName]);
?>
    <h1>Zadania</h1>
    <div class="card mb-3">
        <div class="card-header">Planowanie</div>
        <div class="card-body">

            <button class="btn-primary btn mb-3" data-bind="click: addTask">Dodaj</button>
            <?php
            if ($_GET['touch'] == 'true') {
                $disableTouchButton = '<button class="btn-primary btn mb-3" data-bind="click: disableTouch">Edytuj dotykowo</button>';
                echo $disableTouchButton;
            } else {
                $enableTouchButton = '<button class="btn-primary btn mb-3" data-bind="click: enableTouch">Sortuj dotykowo</button>';
                echo $enableTouchButton;
            }
            ?>

            <div class="list-group" data-bind="sortable: tasks">
                <div class="list-group-item" data-bind="click: edit">
                    <div data-bind="visible: !editing(), html: formatted"></div>
                    <div class="form-group m-0" data-bind="visible: editing">
                        <div class="input-group">
                            <textarea class="form-control" rows="3" minlength="2" maxlength="4000" required
                                      data-bind="value: text, hasFocus: editing"></textarea>
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

        function Task(initialText) {
            var me = this;

            me.text = ko.observable(initialText);

            me.formatted = ko.computed(function () {
                return remarkable.render(me.text());
            });

            me.editing = ko.observable(false);

            me.edit = function () {
                me.editing(true);
            }
        }

        function ViewModel() {
            var me = this;
            me.initialTasksData = JSON.parse(<?= json_encode($toDoList['json']);?>);
            me.initialTasks = jQuery.map(me.initialTasksData, function (task) {
                return new Task(task.text);
            });
            me.tasks = ko.observableArray(me.initialTasks);

            me.addTask = function () {
                var newTask = new Task('Nowe zadanie');
                var current = me.tasks();
                var newTasks = [newTask].concat(current);
                me.tasks(newTasks);

                newTask.edit();
            };

            me.remove = function (task) {
                me.tasks.remove(task);
            };

            me.jsonToDoList = ko.computed(function () {
                var tasks = ko.toJS(me.tasks);
                var tasksData = jQuery.map(tasks, function (task) {
                    return {text: task.text};
                });
                return ko.toJSON(tasksData);
            });

            me.enableTouch = function () {
                window.location = window.location + '&touch=true';
            };

            me.disableTouch = function () {
                var url = window.location.href;
                window.location = url.replace('&touch=true', '');
            }
        }

        var viewModel = new ViewModel();

        ko.applyBindings(viewModel);
    </script>

<?php
include '../../Shared/Views/Footer.php';
?>