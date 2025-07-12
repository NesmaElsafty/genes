<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AnimalView extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'animal_id',
        'external_chars',
        'value',
        'expects',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id', 'id');
    }
} 