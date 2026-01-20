<?php

namespace App\Http\Controllers\Api;

use App\Facades\ApiResponseFacade as ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddDependencyRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="API Endpoints for Task Management"
 * )
 */
class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="List tasks",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string", enum={"pending", "in_progress", "completed", "canceled"})),
     *     @OA\Parameter(name="due_date_from", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="due_date_to", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="assigned_to", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks",
     *         @OA\JsonContent(ref="#/components/schemas/TaskCollection")
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'due_date_from', 'due_date_to', 'assigned_to']);
        $tasks = $this->taskService->getFilteredTasks($filters, $request->user());

        return ApiResponse::success(new TaskCollection($tasks), 'Tasks retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Task")
     *         )
     *     )
     * )
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated(), $request->user());

        return ApiResponse::created(new TaskResource($task), 'Task created successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Get task details",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Task details",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function show(Task $task): JsonResponse
    {
        // Policy check inside controller method or middleware?
        // We can use policy here. For simplicity and as per plan, we check if user has access.
        // TaskService::getFilteredTasks handles list visibility.
        // For single task visibility:
        // Users can see tasks assigned to them? Or all tasks?
        // Requirement: "Users can retrieve only tasks assigned to them"
        $user = request()->user();
        if ($user->isUser() && $task->assigned_to !== $user->id) {
            return ApiResponse::error('Forbidden: You can only view tasks assigned to you', 403);
        }

        $task->load(['assignedTo', 'createdBy', 'dependencies']);
        return ApiResponse::success(new TaskResource($task), 'Task details retrieved');
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update a task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        // Special check for status update vs dependency completion
        if (isset($request->validated()['status']) && $request->validated()['status'] === Task::COMPLETED) {
            if (!$this->taskService->canCompleteTask($task)) {
                return ApiResponse::error('Cannot complete task: Unfinished dependencies', 422);
            }
        }

        $updatedTask = $this->taskService->updateTask($task, $request->validated(), $request->user());

        return ApiResponse::success(new TaskResource($updatedTask), 'Task updated successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/tasks/{id}/dependencies",
     *     summary="Add a dependency to a task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AddDependencyRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dependency added"
     *     )
     * )
     */
    public function addDependency(AddDependencyRequest $request, Task $task): JsonResponse
    {
        $dependsOnTaskId = $request->validated()['depends_on_task_id'];

        if ($this->taskService->hasCircularDependency($task->id, $dependsOnTaskId)) {
            return ApiResponse::error('Circular dependency detected', 422);
        }

        $this->taskService->addDependency($task, $dependsOnTaskId);

        return ApiResponse::success(null, 'Dependency added successfully');
    }
}
