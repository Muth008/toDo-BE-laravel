<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskStatusNoAuthTest extends TestCase
{
    public function test_cannot_access_task_statuses_without_authentication()
    {
        $response = $this->getJson('/api/task-statuses');

        $response->assertStatus(401);
    }
}
