<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Farm extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'farm_user', 'farm_id', 'user_id');
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
