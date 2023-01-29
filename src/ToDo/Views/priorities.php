<?php
include '../../Shared/Views/View.php';

$listName = $_GET['name'];
$toDoList = get("select s.json from to_do_list s where s.name = ?", [$listName]);
?>

<div class="form-group mb-3">
    <form action="../UseCases/SaveToDoListUseCase.php" method="post">
        <div class="form-group">
            <input type="hidden" name="Name" value="<?= $listName ?>"/>
            <input type="hidden" id="ToDoList" name="ToDoList"/>
            <button class="btn-primary btn mb-1">Zapisz</button>
        </div>
    </form>
</div>

<table id="matrix" class="em-matrix">
    <th></th>
    <th>Pilne</th>
    <th>Niepilne</th>
    <tr>
        <th>Ważne</th>
        <td id="urgent-important" class="em-box"></td>
        <td id="not-urgent-important" class="em-box"></td>
    </tr>
    <tr>
        <th>Nieważne</th>
        <td id="urgent-not-important" class="em-box"></td>
        <td id="not-urgent-not-important" class="em-box"> </td>
    </tr>
</table>
</div>

<script src="eisenhower-matrix.js"></script>
<script>
    class Task {

        constructor(text, urgent = false, important = false) {
            this.id = `task-${Math.random()}`;
            this.text = text;
            this.urgent = urgent;
            this.important = important;
        }

        }
    const initialTasks = JSON.parse(<?= json_encode($toDoList['json']);?>);
    const tasks = initialTasks.map((task) => new Task(task.text, task.urgent, task.important));
    const eisenhowerMatrix = new EisenhowerMatrix(tasks);
    const update = (tasks) => {
        const tasksToSave = document.getElementById('ToDoList');
        tasksToSave.value = JSON.stringify(tasks);
    };
    update(tasks);
    eisenhowerMatrix.bind(update);
</script>

<?php
include '../../Shared/Views/Footer.php';
?>