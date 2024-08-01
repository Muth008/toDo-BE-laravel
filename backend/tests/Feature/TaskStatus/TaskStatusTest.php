<?php

namespace Tests\Feature\TaskStatus;

use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user and get the token
        $loginData = $this->createUserAndGetLoginData('admin');
        $this->token = $loginData['token'];
        $this->user = $loginData['user'];
    }

    public function test_can_get_list_of_task_statuses()
    {
        TaskStatus::factory()->count(3)->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->getJson('/api/task-statuses');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'order'
                         ]
                     ]
                 ]);
    }

    public function test_can_create_task_status()
    {
        $statusData = [
            'name' => 'In Progress',
            'order' => 2
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->postJson('/api/task-statuses', $statusData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'name',
                         'order'
                     ]
                 ]);

        $this->assertDatabaseHas('task_statuses', ['name' => 'In Progress', 'order' => 2]);
    }

    public function test_can_get_specific_task_status()
    {
        $status = TaskStatus::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->getJson("/api/task-statuses/{$status->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'name',
                         'order'
                     ]
                 ]);
    }

    public function test_can_update_task_status()
    {
        $status = TaskStatus::factory()->create();

        $updateData = [
            'name' => 'Updated Status',
            'order' => 3
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->putJson("/api/task-statuses/{$status->id}", $updateData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'name',
                         'order'
                     ]
                 ]);

        $this->assertDatabaseHas('task_statuses', [
            'id' => $status->id,
            'name' => 'Updated Status',
            'order' => 3
        ]);
    }

    public function test_can_delete_task_status()
    {
        $status = TaskStatus::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->deleteJson("/api/task-statuses/{$status->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('task_statuses', ['id' => $status->id]);
    }

    public function test_cannot_create_task_status_with_invalid_data()
    {
        $invalidData = [
            'name' => '', // Empty name
            'order' => 'not a number' // Invalid order
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->postJson('/api/task-statuses', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'order']);
    }

    public function test_cannot_update_task_status_with_invalid_data()
    {
        $status = TaskStatus::factory()->create();

        $invalidData = [
            'name' => '', // Empty name
            'order' => 'not a number' // Invalid order
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->putJson("/api/task-statuses/{$status->id}", $invalidData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'order']);
    }

    public function test_cannot_delete_non_existent_task_status()
    {
        $nonExistentId = 9999;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->deleteJson("/api/task-statuses/{$nonExistentId}");

        $response->assertStatus(404);
    }
}
