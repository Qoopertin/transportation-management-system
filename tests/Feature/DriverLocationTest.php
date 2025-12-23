<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DriverLocationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::create(['name' => 'driver']);
    }

    public function test_driver_can_update_location(): void
    {
        $driver = User::factory()->create();
        $driver->assignRole('driver');

        Sanctum::actingAs($driver);

        $response = $this->postJson('/api/driver/location', [
            'latitude' => 34.0522,
            'longitude' => -118.2437,
            'heading' => 180,
            'speed' => 65.5,
            'accuracy' => 10.0,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('driver_locations', [
            'user_id' => $driver->id,
            'latitude' => 34.0522,
            'longitude' => -118.2437,
        ]);
    }

    public function test_location_update_requires_authentication(): void
    {
        $response = $this->postJson('/api/driver/location', [
            'latitude' => 34.0522,
            'longitude' => -118.2437,
        ]);

        $response->assertStatus(401);
    }

    public function test_location_update_validates_coordinates(): void
    {
        $driver = User::factory()->create();
        $driver->assignRole('driver');

        Sanctum::actingAs($driver);

        $response = $this->postJson('/api/driver/location', [
            'latitude' => 999, // invalid
            'longitude' => -118.2437,
        ]);

        $response->assertStatus(422);
    }
}
