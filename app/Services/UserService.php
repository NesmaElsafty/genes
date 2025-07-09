<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ExportHelper;

class UserService
{
    public function getAllUsers($data)
    {
        $query = User::query();
        if (isset($data['search'])) {
            // search in name, email, phone
            $query
                ->where('name', 'like', '%' . $data['search'] . '%')
                ->orWhere('email', 'like', '%' . $data['search'] . '%')
                ->orWhere('phone', 'like', '%' . $data['search'] . '%');
        }
        if (isset($data['role'])) {
            $query->whereHas('roles', function ($query) use ($data) {
                $query->where('name', $data['role']);
            });
        }

        if (isset($data['sorted_by'])) {
            switch ($data['sorted_by']) {
                case 'name':
                    $query->orderBy('name', $data['sorted_by_order'] ?? 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        }

        if (isset($data['is_active'])) {
            $query->where('is_active', $data['is_active']);
        }

        return $query;
    }

    public function stats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_active_users' => User::where('is_active', true)->count(),
            'total_inactive_users' => User::where('is_active', false)->count(),
        ];
        return $stats;
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function createUser($data)
    {
        $role = $data['role'] ?? null;
        unset($data['role']);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        if ($role) {
            $user->syncRoles([$role]);
        }
        return $user;
    }

    public function updateUser($id, $data)
    {
        $role = $data['role'] ?? null;
        unset($data['role']);
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        if ($role) {
            $user->syncRoles([$role]);
        }
        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }
        return $user->delete();
    }

    // users bulk delete
    public function bulkDelete($ids)
    {
        foreach ($ids as $id) {
            $this->deleteUser($id);
        }
        return true;
    }

    // users activate and deactivate toggle
    public function toggleUser($id)
    {
        $user = User::find($id);
        $user->is_active = !$user->is_active;
        $user->save();
        return $user;
    }

    // bulk activate and deactivate
    public function bulkToggle($ids)
    {
        foreach ($ids as $id) {
            $this->toggleUser($id);
        }
        return true;
    }

    public function exportSheet($ids, $user)
    {
        if (empty($ids)) {
            // لو مفيش ids → نجيب كل المستخدمين باـ role المطلوب
            $users = User::all();
        } else {
            // لو فيه ids → نجيب فقط اللي اـ id بتاعه في القامة
            $users = User::whereIn('id', $ids)->get();
        }

        $csvData = [];

        foreach ($users as $user) {
            $csvData[] = [
                'id' => $user->id,
                'role' => $user->roles->pluck('name')->first(),
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'location' => $user->location,
                'is_active' => $user->is_active,
            ];
        }

        $filename = 'users_export_' . now()->format('Ymd_His') . '.csv';
        $media = ExportHelper::exportToMedia($csvData, $user, 'exports', $filename);

        return $media->getFullUrl();
    }
}
