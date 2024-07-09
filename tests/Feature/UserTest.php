<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => true, // validate token format based on your implementation
            ]);
    }

    /** @test */
    public function it_cannot_create_a_user_with_duplicate_email()
    {
        $user = User::factory()->create([
            'email' => 'jane.doe@example.com'
        ]);

        $userData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password456'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'validation error',
            ])
            ->assertJsonValidationErrors(['email']);
    }

    // Add more test cases for createUser method based on the scenarios provided

    /** @test */
    public function it_can_login_a_user()
    {
        $user = User::factory()->create([
            'email' => 'test.user@example.com',
            'password' => Hash::make('password789'),
        ]);

        $userData = [
            'email' => 'test.user@example.com',
            'password' => 'password789'
        ];

        $response = $this->postJson('/api/auth/login', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => true, // validate token format based on your implementation
            ]);
    }

    /** @test */
    public function it_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'email' => 'test.user@example.com',
            'password' => Hash::make('password789'),
        ]);

        $userData = [
            'email' => 'test.user@example.com',
            'password' => 'wrongpassword'
        ];

        $response = $this->postJson('/api/auth/login', $userData);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ]);
    }

    // Add more test cases for loginUser method based on the scenarios provided
}
