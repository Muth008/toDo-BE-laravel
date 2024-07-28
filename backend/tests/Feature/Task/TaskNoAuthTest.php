<?php

namespace Tests\Feature\Task;

use Tests\TestCase;

class TaskNoAuthTest extends TestCase
{
    public function test_cannot_access_tasks_without_authentication()
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }
}
