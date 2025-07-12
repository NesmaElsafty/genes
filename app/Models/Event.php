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
        'animal_id',
        'date',
        'eventType_id',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'eventType_id');
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
} 