<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskPriorityNoAuthTest extends TestCase
{
    public function test_cannot_access_task_priorities_without_authentication()
    {
        $response = $this->getJson('/api/task-priorities');

        $response->assertStatus(401);
    }
}
