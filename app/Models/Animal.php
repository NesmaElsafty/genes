<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Animal extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'animal_id',
        'sir_id',
        'dam_id',
        'gender',
        'farm_id',
        'event_id',
        'breed_id',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function event()
    {
        return $this->belongsTo(EventType::class, 'event_id');
    }

    public function breed()
    {
        return $this->belongsTo(AnimalBreed::class, 'breed_id');
    }
} 