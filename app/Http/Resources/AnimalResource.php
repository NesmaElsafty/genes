<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    public function toArray($request)
{
    $matings = $this->sirMatings;
    if($this->gender != 'male'){
        $matings = $this->damMatings;
    }
    return [
            'id' => $this->id,
            'animal_id' => $this->animal_id,
            'sir_id' => $this->sir_id,
            'dam_id' => $this->dam_id,
            'gender' => $this->gender,
            'farm_id' => $this->farm?->name ?? $this->farm_id,
            'current_event_type' => $this->animalEventHistory->last()->name ?? $this->events->last()?->eventType?->name,
            'breed_id' => $this->breed?->name ?? $this->breed_id,
            // get media images urls only
            'images' => $this->getMedia('images')->map(function ($media) {
                return $media->getFullUrl();
            }),

            'animal_views' => AnimalViewResource::collection($this->animalViews),
            'animal_event_history' => AnimalEventTypeResource::collection($this->animalEventHistory),
            'events' => $this->events ? EventResource::collection($this->events) : null,
            'matings' => AnimalMatingResource::collection($matings),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
