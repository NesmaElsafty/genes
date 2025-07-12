<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farm;
use App\Models\User;

class FarmSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users with the 'client' role
        $clients = User::role('client')->get();
        foreach ($clients as $client) {
            $farms = \App\Models\Farm::factory(2)->create(['user_id' => $client->id]);
            // Attach farms to the client in the farm_user pivot table
            $client->farms()->syncWithoutDetaching($farms->pluck('id')->toArray());
        }
    }
}
