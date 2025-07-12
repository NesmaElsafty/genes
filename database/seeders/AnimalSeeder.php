<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Animal;
use Illuminate\Support\Facades\Storage;

class AnimalSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 animals
        // $animals = Animal::factory(10)->create();

        // // Attach sample files to each animal
        // foreach ($animals as $animal) {
        //     // You can use any local file for demo, here we use a placeholder
        //     $filePath = storage_path('app/public/sample.txt');
        //     if (!file_exists($filePath)) {
        //         file_put_contents($filePath, 'Sample file for animal media');
        //     }
        //     $animal->addMedia($filePath)
        //         ->preservingOriginal()
        //         ->toMediaCollection('files');
        // }
    }
} 