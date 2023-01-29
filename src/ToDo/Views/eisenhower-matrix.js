class EisenhowerMatrix {

    constructor(tasks) {
        this.tasks = tasks;
    }

    bind(update) {
        this.boxesDragDrop();
        this.showTasks();
        this.update = update;
    }

    getMatrix() {
        return document.getElementById('matrix');
    }

    boxesDragDrop() {
        const matrix = this;
        const boxes = document.querySelectorAll('.em-box');
        boxes.forEach((box) => {
            box.addEventListener('dragover', this.dragOverBox);
            box.addEventListener('dragleave', this.dragLeaveBox);
            box.addEventListener('drop', (e) => { matrix.dropIntoBox(e) });
        })
    }

    dragOverBox(event) {
        if (event.target.classList.contains('em-box')) {
            event.preventDefault();
        }
        const box = event.target;
        box.classList.add('em-drag-over');
    }

    dragLeaveBox(event) {
        const box = event.target;
        box.classList.remove('em-drag-over');
    }

    dropIntoBox(event) {
        if (event.target.classList.contains('em-box')) {
            event.preventDefault();
        }
        const taskId = event.dataTransfer.getData("plain/text");
        const task = document.getElementById(taskId);
        const box = event.target;
        box.appendChild(task);
        box.classList.remove('em-drag-over');

        this.updateTask(taskId, box.id);
    }

    updateTask(taskId, boxId) {
        const urgent = !boxId.includes('not-urgent');
        const important = !boxId.includes('not-important');
        const task = this.tasks.find(x => x.id === taskId);
        task.urgent = urgent;
        task.important = important;
        this.update(this.tasks);
    }

    showTasks() {
        this.tasks.filter(x => x.urgent && x.important)
            .forEach(((task) => this.showTask(task, document.getElementById('urgent-important'))));
        this.tasks.filter(x => !x.urgent && x.important)
            .forEach(((task) => this.showTask(task, document.getElementById('not-urgent-important'))));
        this.tasks.filter(x => x.urgent && !x.important)
            .forEach(((task) => this.showTask(task, document.getElementById('urgent-not-important'))));
        this.tasks.filter(x => !x.urgent && !x.important)
            .forEach(((task) => this.showTask(task, document.getElementById('not-urgent-not-important'))));
    }

    showTask(task, container) {
        const taskElement = document.createElement('div');
        taskElement.draggable = true;
        taskElement.classList.add('em-task');
        taskElement.id = task.id;
        taskElement.innerText = task.text;
        taskElement.addEventListener('dragstart', this.taskDragStart);
        container.appendChild(taskElement);
    }

    taskDragStart(event) {
        event.dataTransfer.setData("plain/text", event.target.id);
    }

}