# Task Management Web Application

A complete Task Management (To-Do List) Web Application built with Laravel, featuring a responsive Bootstrap UI and AJAX-powered interactions.

## Features

- ✅ **CRUD Operations**: Create, Read, Update, Delete tasks
- ✅ **Responsive UI**: Bootstrap-based responsive design
- ✅ **AJAX Integration**: No page reloads for all operations
- ✅ **Task Filtering**: Filter by All, Pending, or Completed tasks
- ✅ **Due Date Reminders**: Tasks due within 24 hours are highlighted
- ✅ **REST API**: Complete API endpoints for all operations
- ✅ **Real-time Updates**: Instant UI updates using AJAX

## Technologies Used

- **Backend**: Laravel 10
- **Frontend**: Blade Templates + Bootstrap 5 + JavaScript
- **Database**: MySQL
- **API**: RESTful API with JSON responses
- **AJAX**: Axios for API calls

## Installation & Setup

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL
- Web server (Apache/Nginx)

### Installation Steps

1. **Clone/Download the project**
   ```bash
   cd task-management-app
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   - Copy `.env.example` to `.env`
   - Update database configuration in `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=task_management_db
     DB_USERNAME=root
     DB_PASSWORD=your_password
     ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Create Database**
   ```sql
   CREATE DATABASE task_management_db;
   ```

6. **Run Migrations**
   ```bash
   php artisan migrate
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

8. **Access the Application**
   - Open your browser and go to `http://localhost:8000`

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/tasks` | Get all tasks |
| POST | `/api/tasks` | Create new task |
| GET | `/api/tasks/{id}` | Get specific task |
| PUT | `/api/tasks/{id}` | Update task |
| DELETE | `/api/tasks/{id}` | Delete task |
| PATCH | `/api/tasks/{id}/toggle-complete` | Toggle task completion |

## Database Schema

### Tasks Table
- `id` - Primary key
- `task_name` - Task title (required)
- `description` - Task description (optional)
- `is_completed` - Completion status (boolean, default: false)
- `due_date` - Due date (datetime, optional)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## Features Overview

### 1. Task Management
- Add new tasks with name, description, and due date
- Edit existing tasks
- Mark tasks as complete/incomplete
- Delete tasks
- Filter tasks by status (All, Pending, Completed)

### 2. Due Date Reminders
- Tasks due within 24 hours are highlighted with red border
- Visual indicators for urgent tasks

### 3. Responsive Design
- Mobile-friendly Bootstrap 5 interface
- Card-based layout for tasks
- Dropdown menus for task actions

### 4. AJAX Integration
- All operations performed without page reload
- Real-time UI updates
- Success/error notifications

## Project Structure

```
task-management-app/
├── app/
│   ├── Http/Controllers/
│   │   └── TaskController.php
│   ├── Models/
│   │   └── Task.php
│   └── Providers/
├── config/
├── database/
│   └── migrations/
│       └── 2024_01_01_000000_create_tasks_table.php
├── public/
│   └── index.php
├── resources/
│   └── views/
│       └── tasks/
│           └── index.blade.php
├── routes/
│   ├── api.php
│   └── web.php
└── composer.json
```

## Development Notes

- The application uses Laravel's Eloquent ORM for database operations
- CSRF protection is implemented for web routes
- API routes are protected with rate limiting
- The frontend uses Axios for HTTP requests
- Bootstrap 5 provides responsive styling
- Font Awesome icons enhance the UI

## Future Enhancements

- User authentication and authorization
- Task categories and tags
- File attachments
- Email notifications
- Task sharing and collaboration
- Advanced filtering and search
- Task priorities (High, Medium, Low)
- Export/Import functionality

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
