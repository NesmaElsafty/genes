<?php

namespace App\Services;

use Spatie\Permission\Models\Role;

class RoleService
{
    public function getAll()
    {
        return Role::with('permissions')->get();
    }

    public function getById($id)
    {
        return Role::with('permissions')->find($id);
    }

    public function create($data)
    {
        $role = Role::create(['name' => $data['name']]);
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return $role->load('permissions');
    }

    public function update($id, $data)
    {
        $role = Role::find($id);
        if (!$role) {
            return null;
        }
        if (isset($data['name'])) {
            $role->name = $data['name'];
            $role->save();
        }
        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }
        return $role->load('permissions');
    }

    public function delete($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return false;
        }
        return $role->delete();
    }
} 