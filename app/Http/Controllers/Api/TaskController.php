<?php

namespace App\Http\Controllers\Api;

use App\Facades\ApiResponse;
use App\Constants\HttpStatus;
use App\Constants\TaskStatus;
use App\Facades\Pagination;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddDependencyRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
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

        $pagination = Pagination::preparePagination($tasks);

        return ApiResponse::success(HttpStatus::OK, TaskResource::collection($tasks), 'Tasks retrieved successfully', $pagination);
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

        return ApiResponse::success(HttpStatus::CREATED, TaskResource::make($task), 'Task created successfully');
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
    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id, request()->user());

        if (!$task) {
            return ApiResponse::notFound('Task not found');
        }

        return ApiResponse::success(HttpStatus::OK, TaskResource::make($task), 'Task details retrieved');
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
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $updatedTask = $this->taskService->updateTaskById($id, $request->validated(), $request->user());

        return ApiResponse::success(HttpStatus::OK, TaskResource::make($updatedTask), 'Task updated successfully');
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
    public function addDependency(AddDependencyRequest $request, int $id): JsonResponse
    {
        $this->taskService->addDependencyById($id, $request->validated()['depends_on_task_id'], $request->user());

        return ApiResponse::success(HttpStatus::OK, null, 'Dependency added successfully');
    }
}
