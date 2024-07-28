<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatusRequest;
use App\Http\Resources\TaskStatusResource;
use App\Interfaces\TaskStatusRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="TaskStatuses",
 *     description="API Endpoints of Task Statuses"
 * )
 */
/**
 *  @OA\Schema(
 *     schema="TaskStatusApiResponse",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ApiResponse"),
 *         @OA\Schema(
 *             properties={
 *                 @OA\Property(
 *                      property="data", type="object",
 *                      @OA\Property(ref="#/components/schemas/TaskStatus")
 *                 )
 *             }
 *         )
 *     }
 * )
 */
class TaskStatusController extends Controller
{
    private TaskStatusRepositoryInterface $taskStatusRepositoryInterface;

    public function __construct(TaskStatusRepositoryInterface $taskStatusRepositoryInterface)
    {
        $this->taskStatusRepositoryInterface = $taskStatusRepositoryInterface;
    }

    /**
     * @OA\Get(
     *     path="/task-statuses",
     *     summary="Display a listing of the task statuses",
     *     tags={"TaskStatus"},
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
     *                              @OA\Items(ref="#/components/schemas/TaskStatus")
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
        $taskStatuses = $this->taskStatusRepositoryInterface->index();

        return $this->sendResponse(TaskStatusResource::collection($taskStatuses));
    }

    /**
     * @OA\Post(
     *     path="/task-statuses",
     *     summary="Store a newly created task status",
     *     tags={"TaskStatus"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskStatusRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task status created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskStatusApiResponse")
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
    public function store(TaskStatusRequest $request): JsonResponse
    {
        $taskStatus = $this->taskStatusRepositoryInterface->store($request->all());

        return $this->sendResponse(
            new TaskStatusResource($taskStatus),
            'Task status created successfully.',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/task-statuses/{id}",
     *     summary="Display the specified task status",
     *     tags={"TaskStatus"},
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
    *         @OA\JsonContent(ref="#/components/schemas/TaskStatusApiResponse")
    *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Task status not found."
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $taskStatus = $this->taskStatusRepositoryInterface->getById($id);

        if (!$taskStatus) {
            return $this->sendError('Task status not found.', 404);
        }

        return $this->sendResponse(
            new TaskStatusResource($taskStatus),
            'Task status retrieved successfully.'
        );
    }

    /**
     * @OA\Put(
     *     path="/task-statuses/{id}",
     *     summary="Update the specified task status",
     *     tags={"TaskStatus"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskStatusRequest")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task status updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskStatusApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Task status not found."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(TaskStatusRequest $request, string $id): JsonResponse
    {
        $taskStatus = $this->taskStatusRepositoryInterface->update($request->all(), $id);

        if (!$taskStatus) {
            return $this->sendError('Task status not found.', 404);
        }

        return $this->sendResponse(
            new TaskStatusResource($taskStatus),
            'Task status updated successfully.',
            201
        );
    }

    /**
     * @OA\Delete(
     *     path="/task-statuses/{id}",
     *     summary="Remove the specified task status",
     *     tags={"TaskStatus"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task status deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskApiResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Task status not found."
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $this->taskStatusRepositoryInterface->delete($id);

        return $this->sendResponse(
            null,
            'Task status deleted successfully.',
            204
        );
    }
}

