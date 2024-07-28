<?php

namespace App\Http\Requests\TaskCategory;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="TaskCategoryUpdateRequest",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/TaskCategoryBase"),
 *     }
 * )
 */
class TaskCategoryUpdateRequest extends FormRequest
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
            'name' => 'string|max:255',
            'description' => 'string',
        ];
    }
}
