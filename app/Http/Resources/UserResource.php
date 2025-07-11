<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'farms' => $this->farms->pluck('name'),
            "farms_details" => FarmResource::collection($this->farms),
            'role' => $this->roles->first()?->name,
            'is_active' => $this->is_active == 0 ? false : true,
            'is_blocked' => $this->is_blocked == 0 ? false : true,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 