<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Animal extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
// حدث
    public function event()
    {
        return $this->belongsTo(EventType::class, 'event_id');
    }
// سلاله
    public function breed()
    {
        return $this->belongsTo(AnimalBreed::class, 'breed_id');
    }

// نوع الحدث او الحاله
    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }
// فئة الحيوان
    public function animalType()
    {
        return $this->belongsTo(AnimalType::class, 'animal_type_id');
    }

    public function eventTypes()
    {
        return $this->belongsToMany(EventType::class, 'animal_event_type', 'animal_id', 'event_type_id');
    }

    // animal views
    public function animalViews()
    {
        return $this->hasMany(AnimalView::class, 'animal_id');
    }
} 