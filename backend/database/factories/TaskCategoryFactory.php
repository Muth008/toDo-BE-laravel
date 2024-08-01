<?php

namespace Database\Factories;

use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get the first user
        $firstUser = User::first();

        return [
            'user_id' => $firstUser->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}
