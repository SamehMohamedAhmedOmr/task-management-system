<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TaskService
{
    /**
     * Create a new task.
     */
    public function createTask(array $data, User $createdBy): Task
    {
        return Task::create(array_merge($data, [
            'created_by' => $createdBy->id,
            'status' => Task::PENDING, // Default status
        ]));
    }

    /**
     * Update an existing task.
     */
    public function updateTask(Task $task, array $data, User $user): Task
    {
        // Users can only update status
        if ($user->role->key === Role::USER) {
            $task->update(['status' => $data['status']]);
            return $task->refresh();
        }

        // Managers can update everything
        $task->update($data);
        return $task->refresh();
    }

    /**
     * Get tasks based on filters and user role.
     */
    public function getFilteredTasks(array $filters, User $user): LengthAwarePaginator
    {
        $query = Task::query()->with(['assignedTo', 'createdBy', 'dependencies']);

        // Users can only see their assigned tasks
        if ($user->role->key === Role::USER) {
            $query->where('assigned_to', $user->id);
        }

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['due_date_from'])) {
            $query->whereDate('due_date', '>=', $filters['due_date_from']);
        }

        if (!empty($filters['due_date_to'])) {
            $query->whereDate('due_date', '<=', $filters['due_date_to']);
        }

        if (!empty($filters['assigned_to']) && $user->role->key === Role::MANAGER) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        return $query->paginate(10);
    }

    /**
     * Check if task can be completed based on dependencies.
     */
    public function canCompleteTask(Task $task): bool
    {
        // Check if any dependency is not completed
        $incompleteDependencies = $task->dependencies()
            ->where('status', '!=', Task::COMPLETED)
            ->exists();

        return !$incompleteDependencies;
    }

    /**
     * Add a dependency to a task.
     */
    public function addDependency(Task $task, int $dependsOnTaskId): void
    {
        $task->dependencies()->attach($dependsOnTaskId);
    }

    /**
     * Check for circular dependency.
     */
    public function hasCircularDependency(int $taskId, int $dependsOnTaskId): bool
    {
        if ($taskId === $dependsOnTaskId) {
            return true;
        }

        $dependsOnTask = Task::find($dependsOnTaskId);
        if (!$dependsOnTask) {
            return false;
        }

        // Check if the task we depend on already depends on us (direct cycle)
        if ($dependsOnTask->dependencies()->where('depends_on_task_id', $taskId)->exists()) {
            return true;
        }

        // Deep check (simplified for now, BFS/DFS ideal for deep graphs)
        // For this requirement, checking direct and 1-level deep might be enough or we need full recursion
        // Let's implement a recursive check
        return $this->checkCycleRecursive($taskId, $dependsOnTaskId, []);
    }

    private function checkCycleRecursive(int $originalTaskId, int $currentDependencyId, array $visited): bool
    {
        if ($originalTaskId === $currentDependencyId) {
            return true;
        }

        if (in_array($currentDependencyId, $visited)) {
            return false; // Loop detected in traversal but not necessarily back to original
        }

        $visited[] = $currentDependencyId;
        $task = Task::with('dependencies')->find($currentDependencyId);

        if (!$task) {
            return false;
        }

        foreach ($task->dependencies as $dependency) {
            if ($this->checkCycleRecursive($originalTaskId, $dependency->id, $visited)) {
                return true;
            }
        }

        return false;
    }
}
