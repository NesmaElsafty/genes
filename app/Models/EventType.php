<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'event_types';

    public function events()
    {
        return $this->hasMany(Event::class, 'event_type_id');
    }
} 