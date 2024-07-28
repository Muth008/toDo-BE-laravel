<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function index(array $filters = null, int $perPage = null, int $page = null): LengthAwarePaginator
    {
        $query = $this->applyFilters(Task::query(), $filters);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function getById($id): Task
    {
        return Task::find($id);
    }

    public function store(array $data): Task
    {
        return Task::create($data);
    }

    public function update(array $data, $id): Task
    {
        Task::whereId($id)->update($data);

        return Task::find($id);
    }

    public function delete($id): bool
    {
        return Task::destroy($id);
    }

    public function count(array $filters): int
    {
        $query = $this->applyFilters(Task::query(), $filters);

        return $query->count();
    }

    private function applyFilters($query, array $filters = null)
    {
        
        // get only the tasks that belong to the authenticated user
        $query->whereHas('category', function ($query) {
            $query->where('user_id', auth()->id());
        });

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (isset($filters['description'])) {
            $query->where('description', 'like', '%' . $filters['description'] . '%');
        }
        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (isset($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }
        if (isset($filters['priority_id'])) {
            $query->where('priority_id', $filters['priority_id']);
        }
        if (isset($filters['due_date_from'])) {
            $query->where('due_date', '>=', $filters['due_date_from']);
        }
        if (isset($filters['due_date_to'])) {
            $query->where('due_date', '<=', $filters['due_date_to']);
        }

        return $query;
    }
}