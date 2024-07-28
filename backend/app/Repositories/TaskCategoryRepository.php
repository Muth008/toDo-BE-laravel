<?php

namespace App\Repositories;

use App\Interfaces\TaskCategoryRepositoryInterface;
use App\Models\TaskCategory;
use Illuminate\Database\Eloquent\Collection;

class TaskCategoryRepository implements TaskCategoryRepositoryInterface
{
    public function index(): Collection
    {
        return TaskCategory::all();
    }

    public function getById($id): TaskCategory
    {
       return TaskCategory::find($id);
    }

    public function store(array $data): TaskCategory
    {
       return TaskCategory::create($data);
    }

    public function update(array $data,$id): TaskCategory
    {
        TaskCategory::whereId($id)->update($data);

       return TaskCategory::find($id);
    }
    
    public function delete($id): bool
    {
        return TaskCategory::destroy($id);
    }
}
