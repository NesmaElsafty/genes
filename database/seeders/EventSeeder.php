<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Animal;
use App\Models\EventType;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $animals = Animal::all();
        $eventTypeIds = EventType::pluck('id')->toArray();
        $start = strtotime(date('Y-01-01'));
        $end = time();
        $notes = [
            'تم إعطاء تطعيم',
            'تم فحص الحيوان',
            'تم تسجيل حالة صحية',
            'تم نقل الحيوان',
            'تم علاج الحيوان',
            'تم تسجيل حالة ولادة',
        ];
        foreach ($animals as $animal) {
            $count = rand(2, 5);
            for ($i = 0; $i < $count; $i++) {
                $date = date('Y-m-d', rand($start, $end));
                \App\Models\Event::create([
                    'animal_id' => $animal->id,
                    'date' => $date,
                    'eventType_id' => $eventTypeIds[array_rand($eventTypeIds)],
                    'notes' => $notes[array_rand($notes)],
                ]);
            }
        }
    }
} 