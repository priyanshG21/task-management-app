<?php
// Simple task management application
// This is a basic PHP version that will work with WAMP

// Database configuration
$host = 'localhost';
$dbname = 'task_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $tasks]);
            break;
            
        case 'get':
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $task = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'data' => $task]);
            }
            break;
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'create':
            $task_name = $_POST['task_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $due_date = $_POST['due_date'] ?? null;
            
            if (empty($task_name)) {
                echo json_encode(['success' => false, 'message' => 'Task name is required']);
                exit;
            }
            
            $stmt = $pdo->prepare("INSERT INTO tasks (task_name, description, due_date, is_completed, created_at, updated_at) VALUES (?, ?, ?, 0, NOW(), NOW())");
            $stmt->execute([$task_name, $description, $due_date]);
            
            $task_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->execute([$task_id]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'message' => 'Task created successfully', 'data' => $task]);
            break;
            
        case 'update':
            $id = $_POST['id'] ?? '';
            $task_name = $_POST['task_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $due_date = $_POST['due_date'] ?? null;
            $is_completed = isset($_POST['is_completed']) ? (int)$_POST['is_completed'] : 0;
            
            $stmt = $pdo->prepare("UPDATE tasks SET task_name = ?, description = ?, due_date = ?, is_completed = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$task_name, $description, $due_date, $is_completed, $id]);
            
            $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->execute([$id]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'message' => 'Task updated successfully', 'data' => $task]);
            break;
            
        case 'delete':
            $id = $_POST['id'] ?? '';
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Task deleted successfully']);
            break;
            
        case 'toggle':
            $id = $_POST['id'] ?? '';
            $stmt = $pdo->prepare("UPDATE tasks SET is_completed = NOT is_completed, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$id]);
            
            $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->execute([$id]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'message' => 'Task status updated successfully', 'data' => $task]);
            break;
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .content {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
        }
        
        .task-list {
            margin-top: 30px;
        }
        
        .task-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            transition: transform 0.2s;
        }
        
        .task-item:hover {
            transform: translateX(5px);
        }
        
        .task-item.completed {
            opacity: 0.7;
            border-left-color: #51cf66;
        }
        
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .task-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }
        
        .task-actions {
            display: flex;
            gap: 10px;
        }
        
        .task-description {
            color: #666;
            margin-bottom: 10px;
        }
        
        .task-due-date {
            color: #888;
            font-size: 0.9rem;
        }
        
        .task-due-date.overdue {
            color: #ff6b6b;
            font-weight: 600;
        }
        
        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        
        .error {
            background: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .success {
            background: #e8f5e8;
            color: #2e7d32;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Task Management</h1>
            <p>Organize your tasks efficiently</p>
        </div>
        
        <div class="content">
            <form id="taskForm">
                <div class="form-group">
                    <label for="task_name">Task Name *</label>
                    <input type="text" id="task_name" name="task_name" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="datetime-local" id="due_date" name="due_date">
                </div>
                
                <button type="submit" class="btn">Add Task</button>
            </form>
            
            <div id="message"></div>
            
            <div class="task-list">
                <h3>Your Tasks</h3>
                <div id="tasks"></div>
            </div>
        </div>
    </div>

    <script>
        let tasks = [];
        let editingTaskId = null;

        // Load tasks on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTasks();
        });

        // Handle form submission
        document.getElementById('taskForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const action = editingTaskId ? 'update' : 'create';
            
            if (editingTaskId) {
                formData.append('id', editingTaskId);
            }
            formData.append('action', action);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadTasks();
                    document.getElementById('taskForm').reset();
                    editingTaskId = null;
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                showMessage('An error occurred', 'error');
            });
        });

        function loadTasks() {
            document.getElementById('tasks').innerHTML = '<div class="loading">Loading tasks...</div>';
            
            fetch('?action=list')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tasks = data.data;
                    renderTasks();
                } else {
                    showMessage('Failed to load tasks', 'error');
                }
            })
            .catch(error => {
                showMessage('An error occurred while loading tasks', 'error');
            });
        }

        function renderTasks() {
            const tasksContainer = document.getElementById('tasks');
            
            if (tasks.length === 0) {
                tasksContainer.innerHTML = '<div class="loading">No tasks yet. Add one above!</div>';
                return;
            }
            
            tasksContainer.innerHTML = tasks.map(task => `
                <div class="task-item ${task.is_completed ? 'completed' : ''}">
                    <div class="task-header">
                        <div class="task-name">${task.task_name}</div>
                        <div class="task-actions">
                            <button class="btn btn-success" onclick="toggleTask(${task.id})">
                                ${task.is_completed ? 'Undo' : 'Complete'}
                            </button>
                            <button class="btn" onclick="editTask(${task.id})">Edit</button>
                            <button class="btn btn-danger" onclick="deleteTask(${task.id})">Delete</button>
                        </div>
                    </div>
                    ${task.description ? `<div class="task-description">${task.description}</div>` : ''}
                    ${task.due_date ? `<div class="task-due-date ${isOverdue(task.due_date) ? 'overdue' : ''}">Due: ${formatDate(task.due_date)}</div>` : ''}
                </div>
            `).join('');
        }

        function toggleTask(id) {
            const formData = new FormData();
            formData.append('action', 'toggle');
            formData.append('id', id);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    loadTasks();
                } else {
                    showMessage('Failed to update task', 'error');
                }
            });
        }

        function editTask(id) {
            const task = tasks.find(t => t.id == id);
            if (task) {
                document.getElementById('task_name').value = task.task_name;
                document.getElementById('description').value = task.description || '';
                document.getElementById('due_date').value = task.due_date ? task.due_date.substring(0, 16) : '';
                editingTaskId = id;
                
                document.querySelector('button[type="submit"]').textContent = 'Update Task';
            }
        }

        function deleteTask(id) {
            if (confirm('Are you sure you want to delete this task?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        loadTasks();
                    } else {
                        showMessage('Failed to delete task', 'error');
                    }
                });
            }
        }

        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.innerHTML = `<div class="${type}">${message}</div>`;
            setTimeout(() => {
                messageDiv.innerHTML = '';
            }, 3000);
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        }

        function isOverdue(dateString) {
            const dueDate = new Date(dateString);
            const now = new Date();
            return dueDate < now;
        }
    </script>
</body>
</html>

