<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalEventType extends Model
{
    //
    protected $guarded = [];
    protected $table = 'animal_event_type';


    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
