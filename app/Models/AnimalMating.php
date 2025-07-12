<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AnimalMating extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function sir()
    {
        return $this->belongsTo(Animal::class, 'sir_id');
    }

    public function dam()
    {
        return $this->belongsTo(Animal::class, 'dam_id');
    }
} 