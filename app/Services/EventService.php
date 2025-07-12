<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function getEventById($id)
    {
        return Event::with(['eventType', 'animal'])->find($id);
    }

    public function createEvent($data)
    {
        // update or create by animal_id and eventType_id
        $event = Event::where([
            'animal_id' => $data['animal_id'],
            'eventType_id' => $data['eventType_id']
        ])->first();
        
        if ($event) {
            $event->update([
                'date' => $data['date'],
                'notes' => $data['notes'],
            ]);
        } else {
            $event = Event::create([
                'animal_id' => $data['animal_id'],
                'date' => $data['date'],
                'eventType_id' => $data['eventType_id'],
                'notes' => $data['notes'],
            ]);
        }
        return $event;
    }

    public function updateEvent($id, $data)
    {
        $event = Event::find($id);
        if (!$event) {
            return null;
        }
        
        $event->update([
            'animal_id' => $data['animal_id'],
            'date' => $data['date'],
            'eventType_id' => $data['eventType_id'],
            'notes' => $data['notes'],
        ]);

        return $event;
    }

    public function deleteEvent($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return false;
        }
        return $event->delete();
    }

    public function getEventsByAnimal($animalId)
    {
        return Event::where('animal_id', $animalId)->get();
    }
}
