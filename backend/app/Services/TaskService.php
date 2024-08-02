<?php

namespace App\Services;

use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Resources\TaskResource;
use App\Interfaces\TaskRepositoryInterface;

class TaskService
{
    protected $taskRepositoryInterface;

    public function __construct(TaskRepositoryInterface $taskRepositoryInterface)
    {
        $this->taskRepositoryInterface = $taskRepositoryInterface;
    }

    public function getPaginationData(TaskIndexRequest $request, array $filters): array
    {
        $perPage = $request->query('per_page', env('TASKS_PER_PAGE', 10));
        $page = $request->query('page', 1);

        $tasks = $this->taskRepositoryInterface->index($filters, $perPage, $page);
        $totalTasks = $this->taskRepositoryInterface->count($filters);
        $totalPages = ceil($totalTasks / $perPage);

        return [
            'tasks' => TaskResource::collection($tasks),
            'total_tasks' => $totalTasks,
            'total_pages' => $totalPages,
        ];
    }
}