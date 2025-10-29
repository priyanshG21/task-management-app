<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>TaskFlow - Modern Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --light-bg: #f8fafc;
            --card-shadow: 0 10px 25px rgba(0,0,0,0.1);
            --card-shadow-hover: 0 20px 40px rgba(0,0,0,0.15);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.15) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin: 2rem auto;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: visible;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1.5rem;
            font-weight: 600;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: transform 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary.active {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
            transform: translateY(-1px);
        }

        .task-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-radius: 12px;
            border: none;
            overflow: visible;
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .task-due-soon {
            border-left: 4px solid #ff6b6b !important;
            background: #fff5f5;
        }

        .task-completed {
            opacity: 0.8;
            background: #f8f9fa;
        }

        .task-completed .card-title {
            text-decoration: line-through;
            color: #6c757d;
        }

        .priority-high {
            border-left: 4px solid #ff6b6b;
        }

        .priority-medium {
            border-left: 4px solid #ffd93d;
        }

        .priority-low {
            border-left: 4px solid #6bcf7f;
        }

        .badge {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .stats-card {
            background: var(--primary-gradient);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .loading-spinner {
            width: 3rem;
            height: 3rem;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .fade-in {
            opacity: 1;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 12px 12px 0 0;
            border: none;
        }

        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dropdown-menu {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 2000;
        }

        .dropdown-item {
            border-radius: 4px;
            margin: 0.25rem;
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover {
            background: var(--primary-gradient);
            color: white;
        }

        /* Validation Styles */
        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-control.is-valid {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .valid-feedback {
            display: block;
            color: #28a745;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <div class="container">
                        <a class="navbar-brand" href="#">
                            <i class="fas fa-rocket me-2"></i>TaskFlow
                        </a>
                        <div class="navbar-nav ms-auto">
                            <span class="navbar-text">
                                <i class="fas fa-user-circle me-1"></i>Welcome to your workspace
                            </span>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="container">
            <div class="main-container fade-in">
            <!-- Add Task Form -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-plus-circle me-2"></i>Create New Task
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form id="addTaskForm">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="task_name" name="task_name" placeholder="Enter task name">
                                            <label for="task_name">
                                                <i class="fas fa-tasks me-2"></i>Task Name *
                                            </label>
                                        </div>
                                        <div class="invalid-feedback" id="task_name_error"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="datetime-local" class="form-control" id="due_date" name="due_date" placeholder="Select due date">
                                            <label for="due_date">
                                                <i class="fas fa-calendar-alt me-2"></i>Due Date
                                            </label>
                                        </div>
                                        <div class="invalid-feedback" id="due_date_error"></div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter task description"></textarea>
                                        <label for="description">
                                            <i class="fas fa-align-left me-2"></i>Description
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-plus me-2"></i>Create Task
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Stats -->
            <div class="row mb-5">
                <div class="col-md-8">
                    <div class="btn-group btn-group-lg" role="group">
                        <button type="button" class="btn btn-outline-primary active" data-filter="all">
                            <i class="fas fa-list me-2"></i>All Tasks
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-filter="pending">
                            <i class="fas fa-clock me-2"></i>Pending
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-filter="completed">
                            <i class="fas fa-check-circle me-2"></i>Completed
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number" id="taskCount">0</div>
                        <div class="text-white-50">Total Tasks</div>
                    </div>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="row">
                <div class="col-12">
                    <div id="tasksContainer">
                        <div class="text-center py-5">
                            <div class="loading-spinner mx-auto mb-3"></div>
                            <h5 class="text-muted">Loading your tasks...</h5>
                            <p class="text-muted">Please wait while we fetch your data</p>
                        </div>
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
                            <input type="text" class="form-control" id="edit_task_name" name="task_name">
                            <div class="invalid-feedback" id="edit_task_name_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_due_date" class="form-label">Due Date</label>
                            <input type="datetime-local" class="form-control" id="edit_due_date" name="due_date">
                            <div class="invalid-feedback" id="edit_due_date_error"></div>
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
            setupValidation();
        });

        function setupEventListeners() {
            // Add task form
            document.getElementById('addTaskForm').addEventListener('submit', function(e) {
                e.preventDefault();
                if (validateAddForm()) {
                    addTask();
                }
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

        function setupValidation() {
            // Real-time validation for add form
            const addForm = document.getElementById('addTaskForm');
            const taskNameInput = document.getElementById('task_name');
            const dueDateInput = document.getElementById('due_date');

            // Task name validation
            taskNameInput.addEventListener('input', function() {
                validateField(this, validateTaskName(this.value));
            });

            taskNameInput.addEventListener('blur', function() {
                validateField(this, validateTaskName(this.value));
            });

            // Due date validation
            dueDateInput.addEventListener('change', function() {
                validateField(this, validateDueDate(this.value));
            });

            dueDateInput.addEventListener('blur', function() {
                validateField(this, validateDueDate(this.value));
            });

            // Real-time validation for edit form
            const editForm = document.getElementById('editTaskForm');
            const editTaskNameInput = document.getElementById('edit_task_name');
            const editDueDateInput = document.getElementById('edit_due_date');

            editTaskNameInput.addEventListener('input', function() {
                validateField(this, validateTaskName(this.value), 'edit_');
            });

            editTaskNameInput.addEventListener('blur', function() {
                validateField(this, validateTaskName(this.value), 'edit_');
            });

            editDueDateInput.addEventListener('change', function() {
                validateField(this, validateDueDate(this.value), 'edit_');
            });

            editDueDateInput.addEventListener('blur', function() {
                validateField(this, validateDueDate(this.value), 'edit_');
            });
        }

        function validateTaskName(value) {
            if (!value || value.trim() === '') {
                return { valid: false, message: 'Task name is required' };
            }
            if (value.trim().length < 3) {
                return { valid: false, message: 'Task name must be at least 3 characters long' };
            }
            if (value.trim().length > 100) {
                return { valid: false, message: 'Task name must be less than 100 characters' };
            }
            return { valid: true, message: '' };
        }

        function validateDueDate(value) {
            if (!value) {
                return { valid: true, message: '' }; // Due date is optional
            }
            
            const selectedDate = new Date(value);
            const now = new Date();
            
            if (selectedDate < now) {
                return { valid: false, message: 'Due date cannot be in the past' };
            }
            
            // Check if due date is more than 1 year in the future
            const oneYearFromNow = new Date();
            oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
            
            if (selectedDate > oneYearFromNow) {
                return { valid: false, message: 'Due date cannot be more than 1 year in the future' };
            }
            
            return { valid: true, message: '' };
        }

        function validateField(field, validation, prefix = '') {
            const errorElement = document.getElementById(prefix + field.name + '_error');
            
            if (validation.valid) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.style.display = 'none';
                }
            } else {
                field.classList.remove('is-valid');
                field.classList.add('is-invalid');
                if (errorElement) {
                    errorElement.textContent = validation.message;
                    errorElement.style.display = 'block';
                }
            }
            
            return validation.valid;
        }

        function validateAddForm() {
            const taskName = document.getElementById('task_name');
            const dueDate = document.getElementById('due_date');
            
            const taskNameValid = validateField(taskName, validateTaskName(taskName.value));
            const dueDateValid = validateField(dueDate, validateDueDate(dueDate.value));
            
            return taskNameValid && dueDateValid;
        }

        function validateEditForm() {
            const taskName = document.getElementById('edit_task_name');
            const dueDate = document.getElementById('edit_due_date');
            
            const taskNameValid = validateField(taskName, validateTaskName(taskName.value), 'edit_');
            const dueDateValid = validateField(dueDate, validateDueDate(dueDate.value), 'edit_');
            
            return taskNameValid && dueDateValid;
        }

        function clearValidation(formId) {
            const form = document.getElementById(formId);
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('is-valid', 'is-invalid');
                const errorElement = document.getElementById((formId === 'editTaskForm' ? 'edit_' : '') + input.name + '_error');
                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.style.display = 'none';
                }
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
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h4 class="mb-3">No tasks found</h4>
                        <p class="mb-4">${currentFilter === 'all' ? 'Start by creating your first task above!' : `No ${currentFilter} tasks found.`}</p>
                        ${currentFilter === 'all' ? '<button class="btn btn-primary" onclick="document.getElementById(\'task_name\').focus()"><i class="fas fa-plus me-2"></i>Create First Task</button>' : ''}
                    </div>
                `;
                return;
            }

            container.innerHTML = filteredTasks.map(task => {
                const dueDate = task.due_date ? new Date(task.due_date).toLocaleString() : 'No due date';
                const isDueSoon = task.due_date && new Date(task.due_date) <= new Date(Date.now() + 24 * 60 * 60 * 1000) && new Date(task.due_date) > new Date();
                
                return `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card task-card h-100 ${task.is_completed ? 'task-completed' : ''} ${isDueSoon ? 'task-due-soon' : ''}">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="card-title mb-0 fw-bold ${task.is_completed ? 'text-decoration-line-through' : ''}">${task.task_name}</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" data-bs-toggle="dropdown" style="width: 32px; height: 32px; padding: 0;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" onclick="editTask(${task.id})">
                                                <i class="fas fa-edit me-2"></i>Edit Task
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="toggleComplete(${task.id})">
                                                <i class="fas fa-${task.is_completed ? 'undo' : 'check'} me-2"></i>
                                                ${task.is_completed ? 'Mark Incomplete' : 'Mark Complete'}
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteTask(${task.id})">
                                                <i class="fas fa-trash me-2"></i>Delete Task
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                                ${task.description ? `<p class="card-text text-muted mb-3">${task.description}</p>` : ''}
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                        <small class="text-muted">${dueDate}</small>
                                    </div>
                                    <span class="badge ${task.is_completed ? 'bg-success' : 'bg-warning'} px-3 py-2">
                                        <i class="fas fa-${task.is_completed ? 'check' : 'clock'} me-1"></i>
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
                clearValidation('addTaskForm');
                showAlert('Task added successfully!', 'success');
            } catch (error) {
                console.error('Error adding task:', error);
                
                // Handle validation errors from server
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        const input = document.getElementById(field);
                        const errorElement = document.getElementById(field + '_error');
                        if (input && errorElement) {
                            input.classList.add('is-invalid');
                            errorElement.textContent = errors[field][0];
                            errorElement.style.display = 'block';
                        }
                    });
                    showAlert('Please fix the validation errors', 'warning');
                } else {
                    showAlert('Error adding task', 'danger');
                }
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

            // Clear any previous validation
            clearValidation('editTaskForm');

            new bootstrap.Modal(document.getElementById('editTaskModal')).show();
        }

        async function updateTask() {
            if (!validateEditForm()) {
                showAlert('Please fix the validation errors', 'warning');
                return;
            }

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
                
                // Handle validation errors from server
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        const input = document.getElementById('edit_' + field);
                        const errorElement = document.getElementById('edit_' + field + '_error');
                        if (input && errorElement) {
                            input.classList.add('is-invalid');
                            errorElement.textContent = errors[field][0];
                            errorElement.style.display = 'block';
                        }
                    });
                    showAlert('Please fix the validation errors', 'warning');
                } else {
                    showAlert('Error updating task', 'danger');
                }
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

<?php /**PATH D:\wamp64\www\task-management-app\resources\views/tasks/index.blade.php ENDPATH**/ ?>