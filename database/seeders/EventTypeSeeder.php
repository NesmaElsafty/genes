<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventType;

class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'name' => 'حاله صحيه',
            ],
            [
                'name' => 'حاله حامل',
            ],
            [
                'name' => 'حاله مريضه',
            ],
            [
                'name' => 'حاله متوفيه',
            ],
        ];

        foreach ($events as $event) {
            $eventType = new EventType();
            $eventType->name = $event['name'];
            $eventType->description = 'test description';
            $eventType->save();
        }
    }
} 