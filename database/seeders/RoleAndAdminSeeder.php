<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::create(['name' => 'admin']);
        $client_role = Role::create(['name' => 'client']);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
        ]);

        $admin->roles()->attach($admin_role);

        $token = $admin->createToken('AdminToken')->accessToken;

        $this->command->info("Admin access token: " . $token);
    }
}
