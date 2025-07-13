<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // ['name' => 'in_app_notifications'],
            ['name' => 'animal_registration'],
            ['name' => 'health_event_registration'],
            // ['name' => 'genetic_analysis_done'],
            ['name' => 'farm_data_update'],
            // ['name' => 'general_notifications'],
        ];

        DB::table('settings')->insert($settings);
    }
} 