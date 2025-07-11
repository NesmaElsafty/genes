<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'date',
        'event_type_id',
        'animal_id',
        'note',
    ];

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
} 