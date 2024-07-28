<?php

namespace Tests\Feature\TaskCategory;

use Tests\TestCase;

class TaskCategoryNoAuthTest extends TestCase
{
    public function test_cannot_access_task_categories_without_authentication()
    {
        $response = $this->getJson('/api/task-categories');

        $response->assertStatus(401);
    }
}
