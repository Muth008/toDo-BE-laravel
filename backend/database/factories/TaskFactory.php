<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = TaskCategory::all();
        $statuses = TaskStatus::all();
        $priorities = TaskPriority::all();

        return [
            'category_id' => $categories->random()->id,
            'status_id' => $statuses->random()->id,
            'priority_id' => $priorities->random()->id,
            'name' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'text' => $this->faker->text,
            'due_date' => $this->faker->date,
        ];
    }
}