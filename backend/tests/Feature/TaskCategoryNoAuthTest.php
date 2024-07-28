<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskCategoryNoAuthTest extends TestCase
{
    public function test_cannot_access_task_categories_without_authentication()
    {
        $response = $this->getJson('/api/task-categories');

        $response->assertStatus(401);
    }
}
