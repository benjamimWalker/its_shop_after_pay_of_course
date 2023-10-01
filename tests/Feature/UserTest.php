<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_users_successfully()
    {
        User::factory(10)->create();

        $this->actingAs(User::first())->get(route('users.index'), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'data' => User::all()->toArray()
            ])
            ->assertJsonMissing([
                'data' => [
                    'password'
                ]
            ]);
    }

    public function test_get_user_with_custom_pagination_page_and_per_page()
    {
        User::factory(10)->create();

        $this->actingAs(User::first())->get(route('users.index', [
            'page' => 2,
            'per_page' => 5
        ]), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(5, 'data')
            ->assertJson([
                'data' => User::skip(5)->take(5)->get()->toArray()
            ])
            ->assertJsonMissing([
                'data' => [
                    'password'
                ]
            ]);
    }

    public function test_get_users_with_filter_column_and_filter_order_and_filter_value()
    {
        User::factory(10)->create(['name' => 'user']);
        User::factory(10)->create(['name' => 'admin']);

        $this->actingAs(User::first())->get(route('users.index', [
            'filter_column' => 'name',
            'filter_order' => 'desc',
            'filter_value' => 'admin'
        ]), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'data' => User::where('name', 'admin')->get()->toArray()
            ])
            ->assertJsonMissing([
                'data' => [
                    'password'
                ]
            ]);
    }

    public function test_get_user_successfully()
    {
        User::factory()->create();

        $this->actingAs(User::first())->get(route('users.show', User::first()->id), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ])
            ->assertJson(
                User::first()->toArray()
            )
            ->assertJsonMissing([
                [
                    'password'
                ]
            ]);
    }

    public function test_create_user()
    {
        $user = User::factory()->make();

        $this->actingAs(User::factory()->create())->post(route('users.store'), array_merge($user->toArray(), ['password' => 'password']), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ])
            ->assertJson(
                collect($user->toArray())->except('email_verified_at')->toArray()
            )
            ->assertJsonMissing([
                [
                    'password'
                ]
            ]);

        $this->assertDatabaseHas('users', collect($user->toArray())->except('email_verified_at')->toArray());
    }

    public function test_validate_password_on_create()
    {
        $user = User::factory()->make();

        $this->actingAs(User::factory()->create())->post(route('users.store'), array_merge($user->toArray(), ['password' => '']), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password'
                ]
            ]);
    }

    public function test_update_user_successfully()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('users.update', $user->id), array_merge($user->toArray(), ['name' => 'updated']), [
            'Accept' => 'application/json'
        ])
            ->assertNoContent();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'updated'
        ]);
    }

    public function test_validate_password_on_update()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('users.update', $user->id), array_merge($user->toArray(), ['password' => '']), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password'
                ]
            ]);
    }

    public function test_delete_user_successfully()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->delete(route('users.destroy', $user->id), [
            'Accept' => 'application/json'
        ])
            ->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function test_get_user_with_relationships_successfully()
    {
        $user = User::factory()->has(Store::factory()->count(10))->create();

        $this->actingAs($user)->get(route('users.show', $user->id), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
                'stores' => [
                    '*' => [
                        'id',
                        'name',
                        'address',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJson(
                $user->toArray()
            )
            ->assertJsonMissing([
                [
                    'password'
                ]
            ]);
    }
}
