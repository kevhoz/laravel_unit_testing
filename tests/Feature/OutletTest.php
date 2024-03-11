<?php

namespace Tests\Feature;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OutletTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for authentication
        $this->user = User::factory()->create();

        // Simulate login and get the token
        $token = $this->user->createToken('TestToken')->plainTextToken;

        // Set the default Authorization header for all requests
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);
    }

    /** @test */
    public function can_create_an_outlet()
    {
        $formData = [
            'nama_outlet' => $this->faker->company,
            'lokasi_outlet' => $this->faker->address,
            'pic_outlet' => $this->faker->name,
        ];

        $this->postJson('/api/outlets', $formData)
             ->assertStatus(201)
             ->assertJson($formData);

        $this->assertDatabaseHas('outlets', $formData);
    }

    /** @test */
    public function can_update_an_outlet()
    {
        $outlet = Outlet::factory()->create();

        $updateData = [
            'nama_outlet' => 'Updated Name',
            'lokasi_outlet' => 'Updated Location',
            'pic_outlet' => 'Updated PIC',
        ];

        $this->putJson("/api/outlets/{$outlet->id}", $updateData)
             ->assertStatus(200)
             ->assertJson($updateData);
    }

    /** @test */
    public function can_show_an_outlet()
    {
        $outlet = Outlet::factory()->create();

        $this->getJson("/api/outlets/{$outlet->id}")
             ->assertStatus(200)
             ->assertJson($outlet->toArray());
    }

    /** @test */
    public function can_delete_an_outlet()
    {
        $outlet = Outlet::factory()->create();

        $this->deleteJson("/api/outlets/{$outlet->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('outlets', ['id' => $outlet->id]);
    }

    /** @test */
    public function can_list_outlets()
    {
        $outlets = Outlet::factory()->count(5)->create();

        $this->getJson("/api/outlets")
             ->assertStatus(200)
             ->assertJsonCount(5);
    }
}
