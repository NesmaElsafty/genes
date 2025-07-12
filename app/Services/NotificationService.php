<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Alert;
use App\Helpers\ExportHelper;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public function getAllNotifications($data)
    {
        $query = Notification::query();

        if (isset($data['type']) && in_array($data['type'], ['alert', 'notify'])) {
            $query->where('type', $data['type']);
        }

        if (isset($data['segment'])) {
            $query->where('role', $data['segment']);
        }

        if (isset($data['status']) && in_array($data['status'], ['sent', 'unsent'])) {
            $query->where('status', $data['status']);
        }

        if (isset($data['search'])) {
            $query->where(function ($q) use ($data) {
                $q->where('title', 'like', '%' . $data['search'] . '%')
                  ->orWhere('description', 'like', '%' . $data['search'] . '%');
            });
        }

        if (isset($data['sorted_by'])) {
            switch ($data['sorted_by']) {
                case 'title':
                    $query->orderBy('title', $data['sorted_by_order'] ?? 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function stats()
    {
        $stats = [
            'total_notifications' => Notification::count(),
            'total_sent' => Notification::where('status', 'sent')->count(),
            'total_unsent' => Notification::where('status', 'unsent')->count(),
        ];
        return $stats;
    }

    public function getNotificationById($id)
    {
        return Notification::find($id);
    }

    public function createNotification($data)
    {
        $notification = new Notification();
        $notification->type = $data['type'];
        $notification->title = $data['title'];
        $notification->description = $data['description'];
        $notification->role = $data['role'];
        $notification->status = $data['status'] ?? 'pending';
        $notification->save();

        // Attach users based on role
        $this->attachUsersByRole($notification, $data['role']);

        return $notification;
    }

    public function updateNotification($id, $data)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return null;
        }

        $notification->type = $data['type'] ?? $notification->type;
        $notification->title = $data['title'] ?? $notification->title;
        $notification->description = $data['description'] ?? $notification->description;
        $notification->role = $data['role'] ?? $notification->role;
        $notification->status = $data['status'] ?? $notification->status;
        $notification->save();

        // Update attached users if role changed
        if (isset($data['role']) && $data['role'] !== $notification->role) {
            $notification->users()->detach();
            $this->attachUsersByRole($notification, $data['role']);
        }

        return $notification;
    }

    public function deleteNotification($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return false;
        }

        $notification->delete();
        return true;
    }

    public function bulkDelete($ids)
    {
        foreach ($ids as $id) {
            $this->deleteNotification($id);
        }
        return true;
    }

    public function sendNotification($id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return false;
        }

        // Get users with the specified role
        $users = User::whereHas('roles', function ($query) use ($notification) {
            $query->where('name', $notification->role);
        })->get();

        foreach ($users as $user) {
            // Create alert for each user
            Alert::create([
                'user_id' => $user->id,
                'title' => $notification->title,
                'body' => $notification->description,
                'is_read' => false,
            ]);

            // Update notification_user pivot
            $notification->users()->updateExistingPivot($user->id, [
                'is_sent' => true,
                'sent_at' => now(),
            ]);
        }

        // Update notification status
        $notification->status = 'sent';
        $notification->save();

        return $notification;
    }

    private function attachUsersByRole($notification, $role)
    {
        $users = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })->get();

        $notification->users()->attach($users->pluck('id')->toArray());
    }

    public function exportSheet($ids, $user)
    {
        if (empty($ids)) {
            $notifications = Notification::all();
        } else {
            $notifications = Notification::whereIn('id', $ids)->get();
        }

        $csvData = [];

        foreach ($notifications as $notification) {
            $csvData[] = [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'description' => $notification->description,
                'role' => $notification->role,
                'status' => $notification->status,
                'created_at' => $notification->created_at,
            ];
        }


        $filename = 'notifications_export_' . now()->format('Ymd_His') . '.csv';
        $media = ExportHelper::exportToMedia($csvData, $user, 'exports', $filename);

        return $media->getFullUrl();    }
} 