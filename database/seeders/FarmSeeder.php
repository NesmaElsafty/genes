<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farm;

class FarmSeeder extends Seeder
{
    public function run(): void
    {
        Farm::factory(10)->create();
    }
}
