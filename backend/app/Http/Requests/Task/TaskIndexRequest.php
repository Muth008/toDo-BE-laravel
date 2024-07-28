<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Parameter(
 *     parameter="PerPage",
 *     name="per_page",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer", minimum=1, maximum=100),
 *     description="Number of tasks per page"
 * )
 * @OA\Parameter(
 *     parameter="Page",
 *     name="page",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer", minimum=1),
 *     description="Page number for pagination"
 * )
 * @OA\Parameter(
 *     parameter="Name",
 *     name="name",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string"),
 *     description="Name of the task"
 * )
 * @OA\Parameter(
 *     parameter="Description",
 *     name="description",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string", maxLength=255),
 *     description="Description of the task"
 * )
 * @OA\Parameter(
 *     parameter="CategoryId",
 *     name="category_id",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer"),
 *     description="ID of the task category"
 * )
 * @OA\Parameter(
 *     parameter="PriorityId",
 *     name="priority_id",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer"),
 *     description="ID of the task priority"
 * )
 * @OA\Parameter(
 *     parameter="StatusId",
 *     name="status_id",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="integer"),
 *     description="ID of the task status"
 * )
 * @OA\Parameter(
 *     parameter="DueDateFrom",
 *     name="due_date_from",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string", format="date"),
 *     description="Filter tasks with due date from"
 * )
 * @OA\Parameter(
 *     parameter="DueDateTo",
 *     name="due_date_to",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string", format="date"),
 *     description="Filter tasks with due date to"
 * )
 */
class TaskIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'description' => 'string|max:255',
            'category_id' => 'integer|exists:task_categories,id',
            'priority_id' => 'integer|exists:task_priorities,id',
            'status_id' => 'integer|exists:task_statuses,id',
            'due_date_from' => 'date',
            'due_date_to' => 'date',
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100'
        ];
    }
}
