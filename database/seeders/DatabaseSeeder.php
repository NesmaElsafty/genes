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
        User::create([
            'name' => 'Nesma El Safty',
            'email' => 'nesmaelsafty18@gmail.com',
            'phone' => '966536388494',
            'is_active' => true,
            'password' => Hash::make('123456'),
        ]);
        User::factory(10)->create();
        Farm::factory(20)->create([
            'user_id' => fn() => User::inRandomOrder()->first()->id,
        ]);
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
