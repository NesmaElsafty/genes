<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'event_type_id' => $this->event_type_id,
            'animal_id' => $this->animal_id,
            'note' => $this->note,
            'media' => $this->getMedia('files')->map(function ($media) {
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