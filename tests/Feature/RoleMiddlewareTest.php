<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'client']);
    }

    /** @test */
    public function admin_can_access_admin_routes()
    {
        $admin = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        $response = $this->actingAs($admin)->get('/transactions');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_cannot_access_admin_routes()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $user->roles()->attach(Role::where('name', 'client')->first());

        $response = $this->actingAs($user)->get('/transactions');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_access_own_transactions()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $user->roles()->attach(Role::where('name', 'client')->first());

        $response = $this->actingAs($user)->get('/my-transactions');

        $response->assertStatus(200);
    }
}
