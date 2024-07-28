<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskCategory;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use App\Models\Task;

class TaskTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed task_categories
        TaskCategory::factory()->create([
            'user_id' => 1,
            'name' => 'Work',
            'description' => 'Work tasks',
        ]);

        TaskCategory::factory()->create([
            'user_id' => 1,
            'name' => 'Personal',
            'description' => 'Personal tasks',
        ]);

        // Seed task_priorities
        TaskPriority::factory()->create([
            'name' => 'High',
            'level' => 1,
        ]);

        TaskPriority::factory()->create([
            'name' => 'Medium',
            'level' => 2,
        ]);

        TaskPriority::factory()->create([
            'name' => 'Low',
            'level' => 3,
        ]);

        // Seed task_statuses
        TaskStatus::factory()->create([
            'name' => 'Pending',
            'order' => 1,
        ]);

        TaskStatus::factory()->create([
            'name' => 'In Progress',
            'order' => 2,
        ]);

        TaskStatus::factory()->create([
            'name' => 'Completed',
            'order' => 3,
        ]);

        // Optionally, seed some tasks
        Task::factory()->count(10)->create();
    }
}