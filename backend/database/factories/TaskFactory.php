<?php

namespace Database\Factories;

use App\Models\Task;
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
        return [
            'category_id' => $this->faker->numberBetween(1, 2),
            'status_id' => $this->faker->numberBetween(1, 3),
            'priority_id' => $this->faker->numberBetween(1, 3),
            'name' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'text' => $this->faker->text,
            'due_date' => $this->faker->date,
        ];
    }
}