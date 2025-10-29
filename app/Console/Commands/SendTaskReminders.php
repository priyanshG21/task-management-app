<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for tasks due within 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for tasks due within 24 hours...');

        // Get tasks due within 24 hours
        $dueSoonTasks = Task::where('due_date', '<=', now()->addHours(24))
                           ->where('due_date', '>', now())
                           ->where('is_completed', false)
                           ->get();

        if ($dueSoonTasks->isEmpty()) {
            $this->info('No tasks due within 24 hours.');
            return;
        }

        $this->info("Found {$dueSoonTasks->count()} tasks due within 24 hours.");

        foreach ($dueSoonTasks as $task) {
            try {
                $this->sendReminderEmail($task);
                $this->line("✓ Reminder sent for task: {$task->task_name}");
            } catch (\Exception $e) {
                $this->error("✗ Failed to send reminder for task: {$task->task_name}");
                Log::error("Failed to send task reminder", [
                    'task_id' => $task->id,
                    'task_name' => $task->task_name,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info('Task reminder process completed.');
    }

    /**
     * Send reminder email for a task
     */
    private function sendReminderEmail(Task $task)
    {
        $dueDate = $task->due_date->format('M j, Y \a\t g:i A');
        $hoursUntilDue = $task->due_date->diffInHours(now());

        $subject = "⏰ Task Reminder: {$task->task_name}";
        
        $message = "
        <h2>📋 Task Reminder</h2>
        <p><strong>Task:</strong> {$task->task_name}</p>
        <p><strong>Due:</strong> {$dueDate}</p>
        <p><strong>Time remaining:</strong> {$hoursUntilDue} hours</p>
        ";
        
        if ($task->description) {
            $message .= "<p><strong>Description:</strong> {$task->description}</p>";
        }

        $message .= "
        <p>Please complete this task before the due date.</p>
        <p>Best regards,<br>Task Management System</p>
        ";

        // For now, we'll log the reminder instead of sending actual email
        // In production, you would use: Mail::to($userEmail)->send(new TaskReminderMail($task));
        Log::info("TASK REMINDER", [
            'task_id' => $task->id,
            'task_name' => $task->task_name,
            'due_date' => $dueDate,
            'hours_until_due' => $hoursUntilDue,
            'message' => $message
        ]);

        $this->line("📧 Reminder logged for: {$task->task_name} (Due: {$dueDate})");
    }
}
