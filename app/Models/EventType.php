<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'event_types';

    public function animals()
    {
        return $this->belongsToMany(Animal::class, 'animal_event_type', 'event_type_id', 'animal_id');
    }
} 