<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
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
} 