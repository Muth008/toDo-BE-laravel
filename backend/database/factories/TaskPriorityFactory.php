<?php

namespace Database\Factories;

use App\Models\TaskPriority;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskPriorityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskPriority::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'level' => $this->faker->numberBetween(1, 3),
        ];
    }
}
