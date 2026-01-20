<?php

namespace App\Services;

use App\Models\Role;
use App\Constants\HttpStatus;
use App\Constants\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{

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
     * Create a new task.
     */
    public function createTask(array $data, User $createdBy): Task
    {
        return Task::create(array_merge($data, [
            'created_by' => $createdBy->id,
            'status' => TaskStatus::PENDING, // Default status
        ]));
    }

    /**
     * Get a task by ID with authorization check.
     */
    public function getTaskById(int $taskId, User $user): ?Task
    {
        $task = Task::with(['assignedTo', 'createdBy', 'dependencies'])->find($taskId);

        if (!$task) {
            return null;
        }

        // users can only see their assigned tasks
        if ($user->role->key === Role::USER && $task->assigned_to !== $user->id) {
            return null;
        }

        return $task;
    }

    /**
     * Update a task by ID with authorization check.
     */
    public function updateTaskById(int $taskId, array $data, User $user): Task
    {
        $task = Task::findOrFail($taskId);

        // check if user can update this task
        if (!$this->canUserUpdateTask($task, $user)) {
            abort(HttpStatus::FORBIDDEN, 'Forbidden: You cannot update this task');
        }

        // check for status update vs dependency completion
        if (isset($data['status']) && $data['status'] === TaskStatus::COMPLETED) {
            if (!$this->canCompleteTask($task)) {
                abort(HttpStatus::UNPROCESSABLE_ENTITY, 'Cannot complete task: Unfinished dependencies');
            }
        }

        return $this->updateTask($task, $data, $user);
    }

    /**
     * Update an existing task.
     */
    public function updateTask(Task $task, array $data, User $user): Task
    {
        // users can only update status
        if ($user->role->key === Role::USER) {
            $task->update(['status' => $data['status']]);
            return $task->refresh();
        }

        // managers can update everything
        $task->update($data);
        return $task->refresh();
    }

    /**
     * Add dependency by task ID with authorization check.
     */
    public function addDependencyById(int $taskId, int $dependsOnTaskId, User $user): void
    {
        $task = Task::findOrFail($taskId);

        // check if user can update this task
        if (!$this->canUserUpdateTask($task, $user)) {
            abort(HttpStatus::FORBIDDEN, 'Forbidden: You cannot update this task');
        }

        // check for duplicate dependency
        if ($this->hasDuplicateDependency($taskId, $dependsOnTaskId)) {
            abort(HttpStatus::UNPROCESSABLE_ENTITY, 'This dependency already exists');
        }

        // check for circular dependency
        if ($this->hasCircularDependency($taskId, $dependsOnTaskId)) {
            abort(HttpStatus::UNPROCESSABLE_ENTITY, 'Circular dependency detected');
        }

        $this->addDependency($task, $dependsOnTaskId);
    }

    /**
     * Check if user can update a task.
     */
    public function canUserUpdateTask(Task $task, User $user): bool
    {
        if ($user->role->key === Role::MANAGER) {
            return true;
        }

        return $task->assigned_to === $user->id;
    }



    /**
     * Check if task can be completed based on dependencies.
     */
    public function canCompleteTask(Task $task): bool
    {
        $incompleteDependencies = $task->dependencies()
            ->where('status', '!=', TaskStatus::COMPLETED)
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
     * Check for duplicate dependency.
     */
    public function hasDuplicateDependency(int $taskId, int $dependsOnTaskId): bool
    {
        return Task::find($taskId)
            ->dependencies()
            ->where('depends_on_task_id', $dependsOnTaskId)
            ->exists();
    }

    /**
     * Check for circular dependency.
     */
    public function hasCircularDependency(int $taskId, int $dependsOnTaskId): bool
    {
        if ($taskId === $dependsOnTaskId) {
            return true;
        }

        return $this->checkCycleRecursive($taskId, $dependsOnTaskId, []);
    }

    private function checkCycleRecursive(int $originalTaskId, int $currentDependencyId, array $visited): bool
    {
        if ($originalTaskId === $currentDependencyId) {
            return true;
        }

        if (in_array($currentDependencyId, $visited)) {
            return false;
        }

        $visited[] = $currentDependencyId;
        $task = Task::with('dependencies')->find($currentDependencyId);

        foreach ($task->dependencies as $dependency) {
            if ($this->checkCycleRecursive($originalTaskId, $dependency->id, $visited)) {
                return true;
            }
        }

        return false;
    }
}
