<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_stores_successfully()
    {
        Store::factory(10)->create();

        $this->actingAs(User::factory()->create())->get(route('stores.index'), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'url',
                        'logo',
                        'address',
                        'owner_id',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'data' => Store::all()->toArray()
            ]);
    }

    public function test_get_store_with_custom_pagination_page_and_per_page()
    {
        Store::factory(10)->create();

        $this->actingAs(User::factory()->create())->get(route('stores.index', [
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
                        'url',
                        'logo',
                        'address',
                        'owner_id',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJsonCount(5, 'data')
            ->assertJson([
                'data' => Store::skip(5)->take(5)->get()->toArray()
            ]);
    }

    public function test_get_stores_with_filter_column_and_filter_order_and_filter_value()
    {
        Store::factory(10)->create(['name' => 'Store 1']);
        Store::factory(10)->create(['name' => 'Store 2']);

        $this->actingAs(User::factory()->create())->get(route('stores.index', [
            'filter_column' => 'name',
            'filter_value' => 'Store 1'
        ]), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'data' => Store::where('name', 'Store 1')->get()->toArray()
            ]);
    }

    public function test_get_store_successfully()
    {
        $store = Store::factory()->create();

        $this->actingAs(User::factory()->create())->get(route('stores.show', $store->id), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'url',
                'logo',
                'address',
                'owner_id',
                'created_at',
                'updated_at'
            ])
            ->assertJson(
                $store->toArray()
            );
    }

    public function test_store_can_be_created()
    {
        $store = Store::factory()->make()->makeHidden('owner_id');

        $this->actingAs(User::factory()->create())->post(route('stores.store'), $store->toArray(), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'url',
                'logo',
                'address',
                'created_at',
                'updated_at'
            ])
            ->assertJson(
                $store->toArray()
            );

        $this->assertDatabaseHas('stores', $store->toArray());
    }

    public function test_validation_of_name_on_create()
    {
        $store = Store::factory()->make(['name' => ''])->makeHidden('owner_id');

        $this->actingAs(User::factory()->create())->post(route('stores.store'), $store->toArray(), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'The name field must be a string.'
            ]);
    }

    public function test_validation_of_url_on_create()
    {
        $store = Store::factory()->make(['url' => ''])->makeHidden('owner_id');

        $this->actingAs(User::factory()->create())->post(route('stores.store'), $store->toArray(), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'url' => 'The url field must be a valid URL.'
            ]);
    }

    public function test_validation_of_logo_on_create()
    {
        $store = Store::factory()->make(['logo' => ''])->makeHidden('owner_id');

        $this->actingAs(User::factory()->create())->post(route('stores.store'), $store->toArray(), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'logo' => 'The logo field must be a valid URL.'
            ]);
    }

    public function test_validation_of_address_on_create()
    {
        $store = Store::factory()->make(['address' => ''])->makeHidden('owner_id');

        $this->actingAs(User::factory()->create())->post(route('stores.store'), $store->toArray(), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'address' => 'The address field must be a string.'
            ]);
    }

    public function test_store_can_be_updated()
    {
        $store = Store::factory()->create();

        $this->actingAs(User::factory()->create())->put(route('stores.update', $store->id), [
            'name' => 'Store 1',
            'url' => 'https://store1.com',
            'logo' => 'https://store1.com/logo.png',
            'address' => 'Store 1 Address'
        ], [
            'Accept' => 'application/json'
        ])
            ->assertNoContent();

        $this->assertDatabaseHas('stores', [
            'id' => $store->id,
            'name' => 'Store 1',
            'url' => 'https://store1.com',
            'logo' => 'https://store1.com/logo.png',
            'address' => 'Store 1 Address'
        ]);
    }

    public function test_validation_of_name_on_update()
    {
        $store = Store::factory()->create();

        $this->actingAs(User::factory()->create())->put(route('stores.update', $store->id), [
            'name' => '',
            'url' => 'https://store1.com',
            'logo' => 'https://store1.com/logo.png',
            'address' => 'Store 1 Address'
        ], [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'The name field must be a string.'
            ]);
    }

    public function test_validation_of_url_on_update()
    {
        $store = Store::factory()->create();

        $this->actingAs(User::factory()->create())->put(route('stores.update', $store->id), [
            'name' => 'Store 1',
            'url' => '',
            'logo' => 'https://store1.com/logo.png',
            'address' => 'Store 1 Address'
        ], [
            'Accept' => 'application/json'
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'url' => 'The url field must be a valid URL.'
            ]);
    }

    public function test_delete_store_successfully()
    {
        $store = Store::factory()->create();

        $this->actingAs(User::factory()->create())->delete(route('stores.destroy', $store->id), [], [
            'Accept' => 'application/json'
        ])
            ->assertNoContent();

        $this->assertDatabaseMissing('stores', [
            'id' => $store->id
        ]);
    }

    public function test_get_store_with_owner_relationship_successfully()
    {
        $store = Store::factory()->create();

        $this->actingAs(User::factory()->create())->get(route('stores.show', $store->id), [
            'Accept' => 'application/json'
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'url',
                'logo',
                'address',
                'owner_id',
                'created_at',
                'updated_at',
                'owner' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson(
                $store->toArray()
            );
    }
}
