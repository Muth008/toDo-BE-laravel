<?php

namespace Tests\Feature\TaskStatus;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusNoAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_task_statuses_without_authentication()
    {
        $response = $this->getJson('/api/task-statuses');

        $response->assertStatus(401);
    }

    public function test_cannot_access_task_statuses_as_user()
    {
        $token = $this->createUserAndGetLoginData('user')['token'];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                         ->getJson('/api/task-statuses');

        $response->assertStatus(403);
    }
}
