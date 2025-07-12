<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        // dd($this->eventType);
        return [
            'id' => $this->id,
            'animal_id' => $this->animal_id,
            'date' => $this->date,
            'event_type_name' => $this->eventType?->name,
            'notes' => $this->notes,
        ];
    }
} 