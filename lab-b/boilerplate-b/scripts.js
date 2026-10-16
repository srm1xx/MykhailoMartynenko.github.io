/* Kod wygenerowany za pomocą sztucznej inteligencji */
const todo = {
  tasks: [],
  term: '',

  getFilteredTasks() {
    if (!this.term || this.term.length < 2) {
      return this.tasks;
    }
    return this.tasks.filter(task =>
      task.text.toLowerCase().includes(this.term.toLowerCase())
    );
  },

  draw() {
    const listContainer = document.getElementById('task-list');
    listContainer.innerHTML = '';

    const filtered = this.getFilteredTasks();

    filtered.forEach((task, index) => {
      const originalIndex = this.tasks.indexOf(task);
      const li = document.createElement('li');

      const checkbox = document.createElement('input');
      checkbox.type = 'checkbox';
      checkbox.checked = task.completed || false;
      checkbox.onchange = () => {
        this.tasks[originalIndex].completed = checkbox.checked;
        this.save();
      };

      const span = document.createElement('span');
      span.className = 'task-text';
      span.innerHTML = this.highlightText(task.text, this.term);
      span.onclick = () => this.editTask(originalIndex, span, dateSpan);

      const dateSpan = document.createElement('span');
      dateSpan.className = 'task-date';
      dateSpan.textContent = task.date || '';

      const deleteBtn = document.createElement('button');
      deleteBtn.className = 'delete-btn';
      deleteBtn.textContent = 'usuń';
      deleteBtn.onclick = () => this.delete(originalIndex);

      li.appendChild(checkbox);
      li.appendChild(span);
      li.appendChild(dateSpan);
      li.appendChild(deleteBtn);
      listContainer.appendChild(li);
    });
  },

  add(text, date) {
    if (!text || text.trim().length < 3 || text.trim().length > 255) return;
    if (date && new Date(date) < new Date().setHours(0,0,0,0)) return;

    this.tasks.push({ text: text.trim(), date: date || '', completed: false });
    this.save();
    this.draw();
  },

  delete(index) {
    this.tasks.splice(index, 1);
    this.save();
    this.draw();
  },

  editTask(index, textSpan, dateSpan) {
    const currentText = this.tasks[index].text;
    const currentDate = this.tasks[index].date;

    const input = document.createElement('input');
    input.type = 'text';
    input.value = currentText;
    input.className = 'edit-input';

    const dateInput = document.createElement('input');
    dateInput.type = 'date';
    dateInput.value = currentDate;
    dateInput.className = 'edit-date';

    textSpan.replaceWith(input);
    dateSpan.replaceWith(dateInput);
    input.focus();

    const saveChanges = () => {
      const newText = input.value.trim();
      const newDate = dateInput.value;
      if (newText.length >= 3 && newText.length <= 255) {
        this.tasks[index].text = newText;
        this.tasks[index].date = newDate;
        this.save();
      }
      this.draw();
    };

    input.onblur = (e) => {
      if (!dateInput.contains(e.relatedTarget)) saveChanges();
    };
    dateInput.onblur = (e) => {
      if (!input.contains(e.relatedTarget)) saveChanges();
    };
  },

  save() {
    localStorage.setItem('tasks', JSON.stringify(this.tasks));
  },

  load() {
    const data = localStorage.getItem('tasks');
    if (data) this.tasks = JSON.parse(data);
  },

  highlightText(text, term) {
    if (!term || term.length < 2) return text;
    const regex = new RegExp(`(${term})`, 'gi');
    return text.replace(regex, '<mark style="background-color: #ffe600;">$1</mark>');
  }
};

document.addEventListener('DOMContentLoaded', () => {
  document.todo = todo;
  document.todo.load();
  document.todo.draw();

  const addBtn = document.getElementById('add-task-btn');
  const inputTask = document.getElementById('new-task-input');
  const inputDate = document.getElementById('new-task-date');
  const searchInput = document.getElementById('search-input');

  addBtn.onclick = () => {
    document.todo.add(inputTask.value, inputDate.value);
    inputTask.value = '';
    inputDate.value = '';
  };

  searchInput.oninput = (e) => {
    document.todo.term = e.target.value;
    document.todo.draw();
  };
});
