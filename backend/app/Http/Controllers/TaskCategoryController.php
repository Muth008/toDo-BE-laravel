<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCategory\TaskCategoryCreateRequest;
use App\Http\Requests\TaskCategory\TaskCategoryUpdateRequest;
use App\Http\Resources\TaskCategoryResource;
use App\Interfaces\TaskCategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="TaskCategory",
 *     description="API Endpoints of Task Categories"
 * )
 * @OA\Schema(
 *     schema="TaskCategoryApiResponse",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ApiResponse"),
 *         @OA\Schema(
 *             properties={
 *                 @OA\Property(
 *                      property="data", type="object",
 *                      @OA\Property(ref="#/components/schemas/TaskCategory")
 *                 )
 *             }
 *         )
 *     }
 * )
 */
class TaskCategoryController extends Controller
{
    private TaskCategoryRepositoryInterface $taskCategoryRepositoryInterface;

    public function __construct(TaskCategoryRepositoryInterface $taskCategoryRepositoryInterface)
    {
        $this->taskCategoryRepositoryInterface = $taskCategoryRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/task-categories",
     *     summary="Get list of task categories",
     *     tags={"TaskCategories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
     *                 @OA\Schema(
     *                     properties={
     *                         @OA\Property(
     *                              property="data", type="array",
     *                              @OA\Items(ref="#/components/schemas/TaskCategory")
     *                         )
     *                     }
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(): JsonResponse
    {
        // get only the task categories that belong to the authenticated user
        $filters['user_id'] = auth()->id();

        $taskCategories = $this->taskCategoryRepositoryInterface->index($filters);

        return $this->sendResponse(TaskCategoryResource::collection($taskCategories));
    }

    /**
     * @OA\Post(
     *     path="/task-categories",
     *     summary="Create a new task category",
     *     tags={"TaskCategories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskCategoryCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task category created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskCategoryApiResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validaion error")
     * )
     */
    public function store(TaskCategoryCreateRequest $request): JsonResponse
    {
        $taskCategory = $this->taskCategoryRepositoryInterface->store($request->all());

        return $this->sendResponse(
            new TaskCategoryResource($taskCategory),
            'Task category created successfully.',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/task-categories/{id}",
     *     summary="Get a task category by ID",
     *     tags={"TaskCategories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task category retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskCategoryApiResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $taskCategory = $this->taskCategoryRepositoryInterface->getById($id);

        if ($taskCategory->user_id !== auth()->id()) {
            return $this->sendError('Forbidden.', null, 403);
        }
        if (!$taskCategory) {
            return $this->sendError('Task category not found.');
        }

        return $this->sendResponse(
            new TaskCategoryResource($taskCategory),
            'Task category retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     *     path="/task-categories/{id}",
     *     summary="Update a task category",
     *     tags={"TaskCategories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskCategoryUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task category updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskCategoryApiResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Task not found"),
     *     @OA\Response(response=422, description="Validaion error")
     * )
     */
    public function update(TaskCategoryUpdateRequest $request, string $id): JsonResponse
    {
        $taskCategory = $this->taskCategoryRepositoryInterface->getById($id);

        if ($taskCategory->user_id !== auth()->id()) {
            return $this->sendError('Forbidden.', null, 403);
        }

        $updatedTaskCategory = $this->taskCategoryRepositoryInterface->update($request->all(), $id);

        if (!$updatedTaskCategory) {
            return $this->sendError('Task category not found.');
        }

        return $this->sendResponse(
            new TaskCategoryResource($updatedTaskCategory),
            'Task category updated successfully.',
            201
        );
    }

    /**
     * @OA\Delete(
     *     path="/task-categories/{id}",
     *     summary="Delete a task category",
     *     tags={"TaskCategories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task category deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $taskCategory = $this->taskCategoryRepositoryInterface->getById($id);

        if ($taskCategory && $taskCategory->user_id !== auth()->id()) {
            return $this->sendError('Forbidden.', null, 403);
        }

        $isDeleted = $this->taskCategoryRepositoryInterface->delete($id);

        if (!$isDeleted) {
            return $this->sendError('Task category not found.');
        }

        return $this->sendResponse(
            null,
            'Task category deleted successfully.',
            204
        );
    }
}

