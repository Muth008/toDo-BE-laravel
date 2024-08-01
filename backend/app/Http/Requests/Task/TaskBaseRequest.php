<?php

namespace App\Http\Requests\Task;

/**
 * @OA\Schema(
 *     schema="TaskBase",
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         description="ID of the task category",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="status_id",
 *         type="integer",
 *         description="ID of the task status",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="priority_id",
 *         type="integer",
 *         description="ID of the task priority",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the task",
 *         maxLength=255,
 *         example="Task Name"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the task",
 *         example="Task Description"
 *     ),
 *     @OA\Property(
 *         property="text",
 *         type="string",
 *         description="Detailed text of the task",
 *         example="Detailed Task Text"
 *     ),
 *     @OA\Property(
 *         property="due_date",
 *         type="string",
 *         format="date",
 *         description="Due date of the task",
 *         example="2023-12-31"
 *     )
 * )
 */
class TaskBaseRequest
{
    // This class is only for schema definition purposes
}
