<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdminPermissions = [
            [
                'name' => 'admin-dashboard',
                'guard_name' => 'web',
                'group_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($superAdminPermissions as $permission) {
            Permission::create($permission);
        }
        $superAdminRole = Role::create(['name' => "SUPER ADMIN"]);

    }
}
