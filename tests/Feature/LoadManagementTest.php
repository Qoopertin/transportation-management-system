<?php

namespace Tests\Feature;

use App\Enums\LoadStatus;
use App\Models\Load;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LoadManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'dispatcher']);
        Role::create(['name' => 'driver']);
    }

    public function test_dispatcher_can_create_load(): void
    {
        $dispatcher = User::factory()->create();
        $dispatcher->assignRole('dispatcher');

        $response = $this->actingAs($dispatcher)->post('/loads', [
            'pickup_address' => '123 Test St, City, State 12345',
            'delivery_address' => '456 Dest Ave, Town, State 67890',
            'pickup_at' => now()->addDay()->format('Y-m-d\TH:i'),
            'delivery_at' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'notes' => 'Test load',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('loads', [
            'pickup_address' => '123 Test St, City, State 12345',
        ]);
    }

    public function test_driver_can_view_assigned_loads_only(): void
    {
        $driver = User::factory()->create();
        $driver->assignRole('driver');

        $assignedLoad = Load::factory()->create([
            'assigned_driver_id' => $driver->id,
        ]);

        $otherLoad = Load::factory()->create();

        $response = $this->actingAs($driver)->get('/driver');

        $response->assertStatus(200);
        $response->assertSee($assignedLoad->reference_no);
        $response->assertDontSee($otherLoad->reference_no);
    }

    public function test_load_status_can_be_updated(): void
    {
        $dispatcher = User::factory()->create();
        $dispatcher->assignRole('dispatcher');

        $load = Load::factory()->create([
            'status' => LoadStatus::PENDING,
        ]);

        $response = $this->actingAs($dispatcher)->post("/loads/{$load->id}/update-status", [
            'status' => LoadStatus::ASSIGNED->value,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('loads', [
            'id' => $load->id,
            'status' => LoadStatus::ASSIGNED->value,
        ]);
    }
}
