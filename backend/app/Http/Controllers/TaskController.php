<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskCreateRequest;
use App\Traits\FilterableTask;
use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Task",
 *     description="API Endpoints of Tasks"
 * )
 */
/**
 *  @OA\Schema(
 *     schema="TaskApiResponse",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ApiResponse"),
 *         @OA\Schema(
 *             properties={
 *                 @OA\Property(
 *                      property="data", type="object",
 *                      @OA\Property(ref="#/components/schemas/Task")
 *                 )
 *             }
 *         )
 *     }
 * )
 */
class TaskController extends Controller
{
    use FilterableTask;

    private TaskRepositoryInterface $taskRepositoryInterface;
    
    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Get list of tasks",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/PerPage"),
     *     @OA\Parameter(ref="#/components/parameters/Page"),
     *     @OA\Parameter(ref="#/components/parameters/Name"),
     *     @OA\Parameter(ref="#/components/parameters/Description"),
     *     @OA\Parameter(ref="#/components/parameters/CategoryId"),
     *     @OA\Parameter(ref="#/components/parameters/PriorityId"),
     *     @OA\Parameter(ref="#/components/parameters/StatusId"),
     *     @OA\Parameter(ref="#/components/parameters/DueDateFrom"),
     *     @OA\Parameter(ref="#/components/parameters/DueDateTo"),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data", type="object",
     *                         @OA\Property(property="tasks", type="array", @OA\Items(ref="#/components/schemas/Task")),
     *                         @OA\Property(property="total_tasks", type="integer"),
     *                         @OA\Property(property="total_pages", type="integer")
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function index(TaskIndexRequest $request): JsonResponse
    {
        $perPage = $request->query('per_page', env('TASKS_PER_PAGE', 10));
        $page = $request->query('page', 1);
        $filters = $this->getTasksFilters($request);

        // Retrieve the tasks and the total count
        $tasks = $this->taskRepositoryInterface->index($filters, $perPage, $page);
        $totalTasks = $this->taskRepositoryInterface->count($filters);

        // Calculate the total number of pages
        $totalPages = ceil($totalTasks / $perPage);

        return $this->sendResponse([
            'tasks' => TaskResource::collection($tasks),
            'total_tasks' => $totalTasks,
            'total_pages' => $totalPages,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(TaskCreateRequest $request): JsonResponse
    {
        $task = $this->taskRepositoryInterface->store($request->all());

        return $this->sendResponse(
            new TaskResource($task),
            'Task created successfully.',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Get a task by ID",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Task not found."
     *    )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $task = $this->taskRepositoryInterface->getById($id);

        if (!$task) {
            return $this->sendError('Task not found.', 404);
        }

        return $this->sendResponse(
            new TaskResource($task),
            'Task retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Update a task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Task not found."
     *    ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(TaskUpdateRequest $request, string $id): JsonResponse
    {        
        $task = $this->taskRepositoryInterface->update($request->all(), $id);

        if (!$task) {
            return $this->sendError('Task not found.', 404);
        }

        return $this->sendResponse(
            new TaskResource($task),
            'Task updated successfully.',
            201
        );
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Delete a task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Task ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Task not found."
     *    )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $isDeleted = $this->taskRepositoryInterface->delete($id);

        if (!$isDeleted) {
            return $this->sendError('Task not found.', 404);
        }

        return $this->sendResponse(
            null,
            'Task deleted successfully.',
            204
        );
    }
}