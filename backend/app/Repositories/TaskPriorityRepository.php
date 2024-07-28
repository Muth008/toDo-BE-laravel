<?php

namespace App\Repositories;

use App\Interfaces\TaskPriorityRepositoryInterface;
use App\Models\TaskPriority;
use Illuminate\Database\Eloquent\Collection;

class TaskPriorityRepository implements TaskPriorityRepositoryInterface
{
    public function index(): Collection
    {
        return TaskPriority::all();
    }

    public function getById($id): TaskPriority | null
    {
       return TaskPriority::find($id);
    }

    public function store(array $data): TaskPriority
    {
       return TaskPriority::create($data);
    }

    public function update(array $data,$id): TaskPriority | null
    {
        TaskPriority::whereId($id)->update($data);

       return TaskPriority::find($id);
    }
    
    public function delete($id): bool
    {
        return TaskPriority::destroy($id);
    }
}
