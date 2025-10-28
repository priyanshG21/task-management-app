<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Management App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .task-card {
            transition: all 0.3s ease;
        }
        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .task-due-soon {
            border-left: 4px solid #dc3545 !important;
            background-color: #fff5f5;
        }
        .task-completed {
            opacity: 0.7;
            text-decoration: line-through;
        }
        .priority-high {
            border-left: 4px solid #dc3545;
        }
        .priority-medium {
            border-left: 4px solid #ffc107;
        }
        .priority-low {
            border-left: 4px solid #28a745;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <div class="container">
                        <a class="navbar-brand" href="#">
                            <i class="fas fa-tasks me-2"></i>Task Management App
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <div class="container mt-4">
            <!-- Add Task Form -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-plus me-2"></i>Add New Task
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="addTaskForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="task_name" class="form-label">Task Name *</label>
                                            <input type="text" class="form-control" id="task_name" name="task_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="due_date" class="form-label">Due Date</label>
                                            <input type="datetime-local" class="form-control" id="due_date" name="due_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add Task
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Stats -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary active" data-filter="all">
                            <i class="fas fa-list me-2"></i>All Tasks
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-filter="pending">
                            <i class="fas fa-clock me-2"></i>Pending
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-filter="completed">
                            <i class="fas fa-check me-2"></i>Completed
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="badge bg-info fs-6">
                        <span id="taskCount">0</span> Tasks
                    </div>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="row">
                <div class="col-12">
                    <div id="tasksContainer">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading tasks...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editTaskForm">
                        <input type="hidden" id="edit_task_id" name="id">
                        <div class="mb-3">
                            <label for="edit_task_name" class="form-label">Task Name *</label>
                            <input type="text" class="form-control" id="edit_task_name" name="task_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_due_date" class="form-label">Due Date</label>
                            <input type="datetime-local" class="form-control" id="edit_due_date" name="due_date">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_is_completed" name="is_completed">
                                <label class="form-check-label" for="edit_is_completed">
                                    Mark as completed
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateTask()">Update Task</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Set up CSRF token for Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        axios.defaults.headers.common['Accept'] = 'application/json';

        let tasks = [];
        let currentFilter = 'all';

        // Load tasks on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTasks();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Add task form
            document.getElementById('addTaskForm').addEventListener('submit', function(e) {
                e.preventDefault();
                addTask();
            });

            // Filter buttons
            document.querySelectorAll('[data-filter]').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('[data-filter]').forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.filter;
                    renderTasks();
                });
            });
        }

        async function loadTasks() {
            try {
                const response = await axios.get('/api/tasks');
                tasks = response.data.data;
                renderTasks();
            } catch (error) {
                console.error('Error loading tasks:', error);
                showAlert('Error loading tasks', 'danger');
            }
        }

        function renderTasks() {
            const container = document.getElementById('tasksContainer');
            const taskCount = document.getElementById('taskCount');

            let filteredTasks = tasks;
            if (currentFilter === 'pending') {
                filteredTasks = tasks.filter(task => !task.is_completed);
            } else if (currentFilter === 'completed') {
                filteredTasks = tasks.filter(task => task.is_completed);
            }

            taskCount.textContent = filteredTasks.length;

            if (filteredTasks.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No tasks found</h5>
                        <p class="text-muted">${currentFilter === 'all' ? 'Start by adding a new task!' : `No ${currentFilter} tasks found.`}</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filteredTasks.map(task => {
                const dueDate = task.due_date ? new Date(task.due_date).toLocaleString() : 'No due date';
                const isDueSoon = task.due_date && new Date(task.due_date) <= new Date(Date.now() + 24 * 60 * 60 * 1000) && new Date(task.due_date) > new Date();
                
                return `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card task-card h-100 ${task.is_completed ? 'task-completed' : ''} ${isDueSoon ? 'task-due-soon' : ''}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0 ${task.is_completed ? 'text-decoration-line-through' : ''}">${task.task_name}</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editTask(${task.id})">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="toggleComplete(${task.id})">
                                                <i class="fas fa-${task.is_completed ? 'undo' : 'check'} me-2"></i>
                                                ${task.is_completed ? 'Mark Incomplete' : 'Mark Complete'}
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteTask(${task.id})">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                                ${task.description ? `<p class="card-text text-muted small">${task.description}</p>` : ''}
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>${dueDate}
                                    </small>
                                    <span class="badge bg-${task.is_completed ? 'success' : 'warning'}">
                                        ${task.is_completed ? 'Completed' : 'Pending'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Update container to use grid layout
            container.className = 'row';
        }

        async function addTask() {
            const form = document.getElementById('addTaskForm');
            const formData = new FormData(form);
            
            try {
                const response = await axios.post('/api/tasks', {
                    task_name: formData.get('task_name'),
                    description: formData.get('description'),
                    due_date: formData.get('due_date')
                });

                tasks.unshift(response.data.data);
                renderTasks();
                form.reset();
                showAlert('Task added successfully!', 'success');
            } catch (error) {
                console.error('Error adding task:', error);
                showAlert('Error adding task', 'danger');
            }
        }

        async function editTask(taskId) {
            const task = tasks.find(t => t.id === taskId);
            if (!task) return;

            document.getElementById('edit_task_id').value = task.id;
            document.getElementById('edit_task_name').value = task.task_name;
            document.getElementById('edit_description').value = task.description || '';
            document.getElementById('edit_due_date').value = task.due_date ? task.due_date.slice(0, 16) : '';
            document.getElementById('edit_is_completed').checked = task.is_completed;

            new bootstrap.Modal(document.getElementById('editTaskModal')).show();
        }

        async function updateTask() {
            const form = document.getElementById('editTaskForm');
            const formData = new FormData(form);
            const taskId = formData.get('id');

            try {
                const response = await axios.put(`/api/tasks/${taskId}`, {
                    task_name: formData.get('task_name'),
                    description: formData.get('description'),
                    due_date: formData.get('due_date'),
                    is_completed: formData.get('is_completed') === 'on'
                });

                const taskIndex = tasks.findIndex(t => t.id === taskId);
                if (taskIndex !== -1) {
                    tasks[taskIndex] = response.data.data;
                }

                renderTasks();
                bootstrap.Modal.getInstance(document.getElementById('editTaskModal')).hide();
                showAlert('Task updated successfully!', 'success');
            } catch (error) {
                console.error('Error updating task:', error);
                showAlert('Error updating task', 'danger');
            }
        }

        async function toggleComplete(taskId) {
            try {
                const response = await axios.patch(`/api/tasks/${taskId}/toggle-complete`);
                
                const taskIndex = tasks.findIndex(t => t.id === taskId);
                if (taskIndex !== -1) {
                    tasks[taskIndex] = response.data.data;
                }

                renderTasks();
                showAlert('Task status updated!', 'success');
            } catch (error) {
                console.error('Error toggling task:', error);
                showAlert('Error updating task status', 'danger');
            }
        }

        async function deleteTask(taskId) {
            if (!confirm('Are you sure you want to delete this task?')) return;

            try {
                await axios.delete(`/api/tasks/${taskId}`);
                
                tasks = tasks.filter(t => t.id !== taskId);
                renderTasks();
                showAlert('Task deleted successfully!', 'success');
            } catch (error) {
                console.error('Error deleting task:', error);
                showAlert('Error deleting task', 'danger');
            }
        }

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
    </script>
</body>
</html>
