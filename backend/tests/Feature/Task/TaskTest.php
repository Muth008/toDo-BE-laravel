<?php

namespace Tests\Feature\Task;

use App\Models\Task;
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
        $loginData = $this->createUserAndGetLoginData('user');
        $this->token = $loginData['token'];
        $this->user = $loginData['user'];
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

    public function test_user_can_only_see_own_tasks()
    {
        $ownTaskData = $this->createUserTask();

        $anotherUserData = $this->createSecondUserAndGetLoginData('user');
        $otherTaskData = $this->createUserTask($anotherUserData['user']->id);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $anotherUserData['token']])
                        ->getJson('/api/tasks');

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $otherTaskData['task']->id,
                    'category_id' => $otherTaskData['category']->id,
                ])
                ->assertJsonMissing([
                    'id' => $ownTaskData['task']->id,
                    'category_id' => $ownTaskData['category']->id,
                ]);
    }

    public function test_user_cannot_see_other_users_task()
    {
        $otherTask = $this->createUserTask()['task'];

        $anotherUserData = $this->createSecondUserAndGetLoginData('user');

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $anotherUserData['token']])
                        ->getJson("/api/tasks/{$otherTask->id}");

        $response->assertStatus(403);
    }

    public function test_user_cannot_update_other_users_task()
    {
        $otherTask = $this->createUserTask()['task'];

        $anotherUserData = $this->createSecondUserAndGetLoginData('user');

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $anotherUserData['token']])
                        ->putJson("/api/tasks/{$otherTask->id}", [
                            'name' => 'Attempt to Update',
                            'description' => 'This should not work'
                        ]);

        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_other_users_task()
    {
        $otherTask = $this->createUserTask()['task'];

        $anotherUserData = $this->createSecondUserAndGetLoginData('user');

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $anotherUserData['token']])
                        ->deleteJson("/api/tasks/{$otherTask->id}");

        $response->assertStatus(403);
    }

    private function createUserTask($userId = null)
    {
        $category = TaskCategory::factory()->create(['user_id' => $userId ?? $this->user->id]);
        $status = TaskStatus::factory()->create();
        $priority = TaskPriority::factory()->create();

        return [
            'category' => $category,
            'task' => Task::factory()->create([
                'category_id' => $category->id,
                'status_id' => $status->id,
                'priority_id' => $priority->id
            ])
        ];
    }
}