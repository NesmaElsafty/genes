<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Farm;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first!
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
        // make the admin user
        $admin = User::create([
            'name' => 'Nesma El Safty',
            'email' => 'nesmaelsafty18@gmail.com',
            'phone' => '966536388494',
            'is_active' => true,
            'password' => Hash::make('123456'),
        ]);
        $admin->syncRoles(['admin']);
        
        // Create 10 users and assign roles
        $users = \App\Models\User::factory(10)->create();
        $roles = [
            'admin',
            'client',
        ];
        foreach ($users as $i => $user) {
            $role = $roles[$i < 5 ? 0 : 1];
            $user->syncRoles([$role]);
            // Each user gets 2 farms
            if ($role == 'client') {
                $user->farms()->saveMany(\App\Models\Farm::factory(2)->make());
            }
        }
    }
}
