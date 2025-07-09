<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_own_farm',
            'view_all_farms',
            'add_animals',
            'add_phenotype',
            'add_health_event',
            'view_health',
            'create_users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $client = Role::firstOrCreate(['name' => 'client']);

        // Admin gets all permissions
        $admin->syncPermissions($permissions);

        // Client gets only specific permissions
        $client->syncPermissions([
            'view_own_farm',
            'add_animals',
            'add_phenotype',
            'add_health_event',
            'view_health',
        ]);
    }
}
