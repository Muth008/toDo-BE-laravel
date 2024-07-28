<?php

namespace App\Traits;

use App\Http\Requests\Task\TaskIndexRequest;

trait FilterableTask
{

    /**
     * Get all filter parameters from request
     */
    public function getTasksFilters(TaskIndexRequest $request): array
    {
        return $request->only([
            'name',
            'description',
            'category_id',
            'priority_id',
            'status_id',
            'due_date_from',
            'due_date_to',
            'page',
            'per_page',
        ]);
    }
}
