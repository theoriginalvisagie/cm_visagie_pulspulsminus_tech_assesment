<?php

namespace Modules\Users\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\Users;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_successfully()
    {
        $response = $this->postJson('/api/register-user', [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'jane@example.com',
            'password' => 'StrongP@ss1'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'surname', 'email', 'created_at', 'updated_at']
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'name' => 'Jane',
            'surname' => 'Doe',
        ]);
    }

    /** @test */
    public function registration_fails_with_missing_fields()
    {
        $response = $this->postJson('/api/register-user', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'surname', 'email', 'password']);
    }

    /** @test */
    public function registration_fails_with_weak_password()
    {
        $response = $this->postJson('/api/register-user', [
            'name' => 'Weak',
            'surname' => 'Pass',
            'email' => 'weak@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function registration_fails_with_duplicate_email()
    {
        Users::create([
            'name' => 'Existing',
            'surname' => 'User',
            'email' => 'existing@example.com',
            'password' => Hash::make('StrongP@ss1'),
        ]);

        $response = $this->postJson('/api/register-user', [
            'name' => 'New',
            'surname' => 'User',
            'email' => 'existing@example.com',
            'password' => 'StrongP@ss1',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
