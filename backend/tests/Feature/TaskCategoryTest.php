<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TaskCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and get the token
        $this->user = User::factory()->create();
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
        $this->token = $response->json('data.token');
    }

    public function test_can_get_list_of_task_categories()
    {
        TaskCategory::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->getJson('/api/task-categories');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id',
                             'user_id',
                             'name',
                             'description'
                         ]
                     ],
                 ]);
    }

    public function test_can_create_task_category()
    {
        $categoryData = [
            'user_id' => $this->user->id,
            'name' => 'Test Category',
            'description' => 'Test Description'
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->postJson('/api/task-categories', $categoryData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'user_id',
                         'name',
                         'description'
                     ],
                 ]);
        
        $this->assertDatabaseHas('task_categories', ['name' => 'Test Category']);
    }

    public function test_can_get_specific_task_category()
    {
        $category = TaskCategory::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->getJson("/api/task-categories/{$category->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'user_id',
                         'name',
                         'description'
                     ],
                 ]);
    }

    public function test_can_update_task_category()
    {
        $category = TaskCategory::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'user_id' => $this->user->id,
            'name' => 'Updated Category Name',
            'description' => 'Updated Description'
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->putJson("/api/task-categories/{$category->id}", $updateData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'user_id',
                         'name',
                         'description'
                     ],
                 ]);

        $this->assertDatabaseHas('task_categories', [
            'id' => $category->id,
            'name' => 'Updated Category Name',
            'description' => 'Updated Description'
        ]);
    }

    public function test_can_delete_task_category()
    {
        $category = TaskCategory::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->deleteJson("/api/task-categories/{$category->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('task_categories', ['id' => $category->id]);
    }

    public function test_cannot_delete_non_existent_task_category()
    {
        $nonExistentId = 9999;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->deleteJson("/api/task-categories/{$nonExistentId}");

        $response->assertStatus(404);
    }
}