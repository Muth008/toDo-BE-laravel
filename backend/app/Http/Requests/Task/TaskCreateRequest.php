<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="TaskCreateRequest",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/TaskBase"),
 *     },
 *     required={"category_id", "name"}
 * )
 */
class TaskCreateRequest extends FormRequest
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
            'category_id' => 'required|integer|exists:task_categories,id',
            'status_id' => 'integer|exists:task_statuses,id',
            'priority_id' => 'integer|exists:task_priorities,id',
            'name' => 'required|string|max:255',
            'description' => 'string',
            'text' => 'string',
            'due_date' => 'date',
        ];
    }
}
