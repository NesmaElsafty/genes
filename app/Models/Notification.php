<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'role',
        'status',
    ];

    protected $casts = [
        'type' => 'string',
        'status' => 'string',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')
                    ->withPivot('is_sent', 'sent_at')
                    ->withTimestamps();
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
} 