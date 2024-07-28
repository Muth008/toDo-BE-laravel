<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function createUserAndGetLoginData($role)
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => $role
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        return [
            'user' => $user,
            'token' => $response->json('data.token')
        ];
    }

    public function createSecondUserAndGetLoginData($role)
    {
        $user = User::factory()->create([
            'email' => 'test2@example.com',
            'password' => bcrypt('password123'),
            'role' => $role
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        return [
            'user' => $user,
            'token' => $response->json('data.token')
        ];
    }
}
