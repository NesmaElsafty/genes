<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Farm extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'user_id',
        'city',
        'location',
        'postal_code',
        'capacity',
        'animal_type_name',
        'animal_breed_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
