<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::factory(10)->create();
        foreach ($events as $event) {
            $filePath = storage_path('app/public/sample_event.txt');
            if (!file_exists($filePath)) {
                file_put_contents($filePath, 'Sample file for event media');
            }
            $event->addMedia($filePath)
                ->preservingOriginal()
                ->toMediaCollection('files');
        }
    }
} 