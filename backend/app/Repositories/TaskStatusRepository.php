<?php

namespace App\Repositories;

use App\Interfaces\TaskStatusRepositoryInterface;
use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Collection;

class TaskStatusRepository implements TaskStatusRepositoryInterface
{
    public function index(): Collection
    {
        return TaskStatus::all();
    }

    public function getById($id): TaskStatus
    {
       return TaskStatus::find($id);
    }

    public function store(array $data): TaskStatus
    {
       return TaskStatus::create($data);
    }

    public function update(array $data,$id): TaskStatus
    {
       TaskStatus::whereId($id)->update($data);

       return TaskStatus::find($id);
    }
    
    public function delete($id): bool
    {
        return TaskStatus::destroy($id);
    }
}
