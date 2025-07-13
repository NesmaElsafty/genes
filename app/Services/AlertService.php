<?php

namespace App\Services;

use App\Models\Alert;
use Illuminate\Support\Facades\Auth;

class AlertService
{
    public function createAlert($data, $userId)
    {
        $alert = new Alert();
        $alert->user_id = $userId;
        $alert->title = $data['title'];
        $alert->body = $data['body'];
        $alert->is_read = $data['is_read'];
        $alert->save();

        return $alert;
    }

    public function getUserAlerts($userId)
    {
        return Alert::where('user_id', $userId)
                   ->orderBy('created_at', 'desc');
    }

    public function getAlertById($id, $userId)
    {
        return Alert::where('id', $id)
                   ->where('user_id', $userId)
                   ->first();
    }

    public function markAsRead($id, $userId)
    {
        $alert = Alert::where('id', $id)
                     ->where('user_id', $userId)
                     ->first();

        if (!$alert) {
            return false;
        }

        $alert->is_read = true;
        $alert->save();

        return $alert;
    }

    public function getUnreadCount($userId)
    {
        return Alert::where('user_id', $userId)
                   ->where('is_read', false)
                   ->count();
    }
} 