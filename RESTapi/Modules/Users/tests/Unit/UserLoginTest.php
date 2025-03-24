<?php

namespace Modules\Users\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLoginTest extends TestCase
{

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/login-user', [
            'email' => 'mail@mailssss.com',
            'password' => 'Camel1!ghts',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'token',
            'user'
        ]);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login-user', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid credentials']);
    }

    /** @test */
    public function login_fails_when_fields_are_missing()
    {
        $response = $this->postJson('/api/login-user', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    /** @test */
    public function login_fails_if_user_not_found()
    {
        $response = $this->postJson('/api/login-user', [
            'email' => 'nonexistent@example.com',
            'password' => 'anything'
        ]);

        $response->assertStatus(401); // Still returns 'Invalid credentials'
    }
}
