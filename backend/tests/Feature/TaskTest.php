<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskCategory;
use App\Models\TaskPriority;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
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

    public function test_can_get_list_of_tasks()
    {
        $category = TaskCategory::factory()->create(['user_id' => $this->user->id]);
        $status = TaskStatus::factory()->create();
        $priority = TaskPriority::factory()->create();

        Task::factory()->count(5)->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
            'priority_id' => $priority->id
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'tasks' => [
                             '*' => [
                                 'id',
                                 'category_id',
                                 'status_id',
                                 'priority_id',
                                 'name',
                                 'description',
                                 'text',
                                 'due_date'
                             ]
                         ],
                         'total_tasks',
                         'total_pages'
                     ]
                 ]);
    }

    public function test_can_create_task()
    {
        $category = TaskCategory::factory()->create(['user_id' => $this->user->id]);
        $status = TaskStatus::factory()->create();
        $priority = TaskPriority::factory()->create();

        $taskData = [
            'category_id' => $category->id,
            'status_id' => $status->id,
            'priority_id' => $priority->id,
            'name' => 'Test Task',
            'description' => 'Test Description',
            'text' => 'Detailed task text',
            'due_date' => '2023-12-31'
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'category_id',
                         'status_id',
                         'priority_id',
                         'name',
                         'description',
                         'text',
                         'due_date'
                     ],
                     'message'
                 ]);
        
        $this->assertDatabaseHas('tasks', ['name' => 'Test Task']);
    }

    public function test_can_get_specific_task()
    {
        $category = TaskCategory::factory()->create(['user_id' => $this->user->id]);
        $status = TaskStatus::factory()->create();
        $priority = TaskPriority::factory()->create();

        $task = Task::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
            'priority_id' => $priority->id
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'category_id',
                         'status_id',
                         'priority_id',
                         'name',
                         'description',
                         'text',
                         'due_date'
                     ]
                 ]);
    }

    public function test_can_update_task()
    {
        $category = TaskCategory::factory()->create(['user_id' => $this->user->id]);
        $status = TaskStatus::factory()->create();
        $priority = TaskPriority::factory()->create();

        $task = Task::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
            'priority_id' => $priority->id
        ]);

        $newCategory = TaskCategory::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'category_id' => $newCategory->id,
            'name' => 'Updated Task Name',
            'description' => 'Updated Description'
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->putJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id',
                         'category_id',
                         'status_id',
                         'priority_id',
                         'name',
                         'description',
                         'text',
                         'due_date'
                     ]
                 ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated Task Name',
            'description' => 'Updated Description'
        ]);
    }

    public function test_can_delete_task()
    {
        $category = TaskCategory::factory()->create(['user_id' => $this->user->id]);
        $status = TaskStatus::factory()->create();
        $priority = TaskPriority::factory()->create();

        $task = Task::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
            'priority_id' => $priority->id
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_can_filter_tasks()
    {
        $category = TaskCategory::factory()->create(['user_id' => $this->user->id]);
        $priority = TaskPriority::factory()->create();
        $status = TaskStatus::factory()->create();

        Task::factory()->count(5)->create([
            'category_id' => $category->id,
            'priority_id' => $priority->id,
            'status_id' => $status->id,
            'name' => 'FilteredTask',
            'due_date' => '2023-12-31'
        ]);

        Task::factory()->count(3)->create([
            'category_id' => $category->id,
            'priority_id' => $priority->id,
            'status_id' => $status->id,
            'name' => 'OtherTask',
            'due_date' => '2024-12-31'
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->getJson("/api/tasks?name=FilteredTask&category_id={$category->id}&priority_id={$priority->id}&status_id={$status->id}&due_date_from=2023-01-01&due_date_to=2023-12-31");

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data.tasks')
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'tasks',
                         'total_tasks',
                         'total_pages'
                     ]
                 ]);
    }

    public function test_cannot_delete_non_existent_task()
    {
        $nonExistentTaskId = 9999;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
                         ->deleteJson("/api/tasks/{$nonExistentTaskId}");

        $response->assertStatus(404);
    }
}