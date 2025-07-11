<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FarmResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_name' => $this->users?->pluck('name'),
            'city' => $this->city,
            'location' => $this->location,
            'postal_code' => $this->postal_code,
            'capacity' => $this->capacity,
            'animal_types' => $this->animal_types ? json_decode($this->animal_types) : [],
            'animal_breeds' => $this->animal_breeds ? json_decode($this->animal_breeds) : [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 