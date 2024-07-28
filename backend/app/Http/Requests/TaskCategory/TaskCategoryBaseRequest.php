<?php

namespace App\Http\Requests\TaskCategory;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="TaskCategoryBase",
 *     type="object",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string")
 * )
 */
class TaskCategoryBaseRequest extends FormRequest
{
    // This class is only for schema definition purposes
}
