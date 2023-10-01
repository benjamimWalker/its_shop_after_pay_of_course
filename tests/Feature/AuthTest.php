<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_invalid_credentials()
    {
        $this->post(route('auth.login'), [
            'email' => 'foo@bar.com',
            'password' => 'password'
        ], [
            'Accept' => 'application/json'
        ])
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }

    public function test_login_successfully()
    {
        $user = User::factory()->create();

        $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ], [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type'
            ]);
    }

    public function test_signup_successfully()
    {
        $this->post(route('auth.signup'), [
            'name' => 'John Doe',
            'email' => 'foo@bar.com'
        ], [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The password field is required.',
                'errors' => [
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ]);
    }

    public function test_signup_with_invalid_data()
    {
        $this->post(route('auth.signup'), [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'password'
        ], [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The email field is required.',
                'errors' => [
                    'email' => [
                        'The email field is required.'
                    ],
                ]
            ]);
    }
}
