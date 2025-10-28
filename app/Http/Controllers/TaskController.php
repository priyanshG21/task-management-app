<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks
     */
    public function index(Request $request): JsonResponse
    {
        $query = Task::query();

        // Filter by completion status
        if ($request->has('status')) {
            if ($request->status === 'completed') {
                $query->completed();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after:now',
        ]);

        $task = Task::create([
            'task_name' => $request->task_name,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'is_completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified task
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $request->validate([
            'task_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'is_completed' => 'sometimes|boolean',
        ]);

        $task->update($request->only(['task_name', 'description', 'due_date', 'is_completed']));

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task
        ]);
    }

    /**
     * Remove the specified task
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    /**
     * Toggle task completion status
     */
    public function toggleComplete(Task $task): JsonResponse
    {
        $task->update(['is_completed' => !$task->is_completed]);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully',
            'data' => $task
        ]);
    }
}
