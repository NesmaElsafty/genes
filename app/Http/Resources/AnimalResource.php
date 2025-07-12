<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnimalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'animal_id' => $this->animal_id,
            'sir_id' => $this->sir_id,
            'dam_id' => $this->dam_id,
            'gender' => $this->gender,
            'farm_id' => $this->farm?->name ?? $this->farm_id,
            'event_id' => $this->event?->name ?? $this->event_id,
            'breed_id' => $this->breed?->name ?? $this->breed_id,
            'media' => $this->getMedia('images')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'file_name' => $media->file_name,
                    'url' => $media->getFullUrl(),
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
