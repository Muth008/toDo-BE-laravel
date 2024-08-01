<?php

namespace App\Repositories;

use App\Interfaces\TaskCategoryRepositoryInterface;
use App\Models\TaskCategory;
use Illuminate\Database\Eloquent\Collection;

class TaskCategoryRepository implements TaskCategoryRepositoryInterface
{
    public function index(array $filters = null): Collection
    {
        $query = $this->applyFilters(TaskCategory::query(), $filters);

        return $query->get();
    }

    public function getById($id): TaskCategory | null
    {
        return TaskCategory::find($id);
    }

    public function store(array $data): TaskCategory
    {
        return TaskCategory::create($data);
    }

    public function update(array $data, $id): TaskCategory | null
    {
        TaskCategory::whereId($id)->update($data);

        return TaskCategory::find($id);
    }

    public function delete($id): bool
    {
        return TaskCategory::destroy($id);
    }

    private function applyFilters($query, $filters = null)
    {
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query;
    }
}
