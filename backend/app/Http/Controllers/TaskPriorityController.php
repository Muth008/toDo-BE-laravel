<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskPriorityRequest;
use App\Http\Resources\TaskPriorityResource;
use App\Interfaces\TaskPriorityRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="TaskPriorities",
 *     description="API Endpoints of Task Priorities"
 * )
 */
/**
 *  @OA\Schema(
 *     schema="TaskPriorityApiResponse",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ApiResponse"),
 *         @OA\Schema(
 *             properties={
 *                 @OA\Property(
 *                      property="data", type="object",
 *                      @OA\Property(ref="#/components/schemas/TaskPriority")
 *                 )
 *             }
 *         )
 *     }
 * )
 */
class TaskPriorityController extends Controller
{
    private TaskPriorityRepositoryInterface $taskPriorityRepositoryInterface;

    public function __construct(TaskPriorityRepositoryInterface $taskPriorityRepositoryInterface)
    {
        $this->taskPriorityRepositoryInterface = $taskPriorityRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/task-priorities",
     *     summary="Display a listing of the task priorities",
     *     tags={"TaskPriority"},
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
     *                              @OA\Items(ref="#/components/schemas/TaskPriority")
     *                         )
     *                     }
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $taskPriorities = $this->taskPriorityRepositoryInterface->index();

        return $this->sendResponse(TaskPriorityResource::collection($taskPriorities));
    }

    /**
     * @OA\Post(
     *     path="/task-priorities",
     *     summary="Store a newly created task priority",
     *     tags={"TaskPriority"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskPriorityRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task priority created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskPriorityApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(TaskPriorityRequest $request): JsonResponse
    {
        $taskPriority = $this->taskPriorityRepositoryInterface->store($request->all());

        return $this->sendResponse(
            new TaskPriorityResource($taskPriority),
            'Task priority created successfully.',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/task-priorities/{id}",
     *     summary="Display the specified task priority",
     *     tags={"TaskPriority"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/TaskPriorityApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Task priority not found."
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $taskPriority = $this->taskPriorityRepositoryInterface->getById($id);

        return $this->sendResponse(
            new TaskPriorityResource($taskPriority),
            'Task priority retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     *     path="/task-priorities/{id}",
     *     summary="Update the specified task priority",
     *     tags={"TaskPriority"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskPriorityRequest")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task priority updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskPriorityApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Task priority not found."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(TaskPriorityRequest $request, string $id): JsonResponse
    {
        $taskPriority = $this->taskPriorityRepositoryInterface->update($request->all(), $id);

        if (!$taskPriority) {
            return $this->sendError('Task priority not found.', 404);
        }

        return $this->sendResponse(
            new TaskPriorityResource($taskPriority),
            'Task priority updated successfully.',
            201
        );
    }

    /**
     * @OA\Delete(
     *     path="/task-priorities/{id}",
     *     summary="Remove the specified task priority",
     *     tags={"TaskPriority"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task priority deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Task priority not found."
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $isDeleted = $this->taskPriorityRepositoryInterface->delete($id);

        if (!$isDeleted) {
            return $this->sendError('Task priority not found.', 404);
        }

        return $this->sendResponse(
            null,
            'Task priority deleted successfully.',
            204
        );
    }
}

