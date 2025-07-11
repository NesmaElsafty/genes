<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Models\Animal;

class EventService
{
    public function getAllEvents($perPage = 10)
    {
        return Event::with(['eventType', 'animal'])->paginate($perPage);
    }

    public function getEventById($id)
    {
        return Event::with(['eventType', 'animal'])->find($id);
    }

    public function createEvent($data)
    {
        DB::transaction(function () use ($data) {
            $animal = Animal::where('animal_id', $data['animal_id'])->first();
            $animalId = $animal->id;

            $event = Event::create([
                'date' => $data['date'],
                'event_type_id' => $data['event_type_id'],
                'animal_id' => $animalId,
                'note' => $data['note'] ?? null,
            ]);
            return $event;
        });
    }

    public function updateEvent($id, $data, $files = null)
    {
        return DB::transaction(function () use ($id, $data, $files) {
            $event = Event::find($id);
            if (!$event) {
                return null;
            }
            $event->update([
                'date' => $data['date'] ?? $event->date,
                'event_type_id' => $data['event_type_id'] ?? $event->event_type_id,
                'animal_id' => $data['animal_id'] ?? $event->animal_id,
                'note' => $data['note'] ?? $event->note,
            ]);
            return $event;
        });
    }

    public function deleteEvent($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return false;
        }
        return $event->delete();
    }
}
