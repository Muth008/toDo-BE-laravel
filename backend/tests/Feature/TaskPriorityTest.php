<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TaskPriority;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPriorityTest extends TestCase
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

    public function test_can_get_list_of_task_priorities()
    {
        TaskPriority::factory()->count(3)->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->getJson('/api/task-priorities');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'level'
                         ]
                     ]
                 ]);
    }

    public function test_can_create_task_priority()
    {
        $priorityData = [
            'name' => 'High Priority',
            'level' => 3
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->postJson('/api/task-priorities', $priorityData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'name',
                         'level'
                     ]
                 ]);
        
        $this->assertDatabaseHas('task_priorities', ['name' => 'High Priority', 'level' => 3]);
    }

    public function test_can_get_specific_task_priority()
    {
        $priority = TaskPriority::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->getJson("/api/task-priorities/{$priority->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'name',
                         'level'
                     ]
                 ]);
    }

    public function test_can_update_task_priority()
    {
        $priority = TaskPriority::factory()->create();

        $updateData = [
            'name' => 'Updated Priority',
            'level' => 4
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->putJson("/api/task-priorities/{$priority->id}", $updateData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'name',
                         'level'
                     ]
                 ]);

        $this->assertDatabaseHas('task_priorities', [
            'id' => $priority->id,
            'name' => 'Updated Priority',
            'level' => 4
        ]);
    }

    public function test_can_delete_task_priority()
    {
        $priority = TaskPriority::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->deleteJson("/api/task-priorities/{$priority->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('task_priorities', ['id' => $priority->id]);
    }

    public function test_cannot_create_task_priority_with_invalid_data()
    {
        $invalidData = [
            'name' => '', // Empty name
            'level' => 'not a number' // Invalid level
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->postJson('/api/task-priorities', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'level']);
    }

    public function test_cannot_update_task_priority_with_invalid_data()
    {
        $priority = TaskPriority::factory()->create();

        $invalidData = [
            'name' => '', // Empty name
            'level' => 'not a number' // Invalid level
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->putJson("/api/task-priorities/{$priority->id}", $invalidData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'level']);
    }

}