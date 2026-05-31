<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-applications',
            'evaluate-applications',
            'mark-eligible',
            'accept-applications',
            'reject-applications',
            'scan-attendance',
            'manage-content',
            'manage-system',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'super_admin' => $permissions,
            'committee_president' => ['view-applications', 'evaluate-applications', 'accept-applications', 'reject-applications'],
            'committee_member' => ['view-applications', 'evaluate-applications'],
            'admin_dgce' => ['view-applications', 'mark-eligible'],
            'communication' => ['manage-content'],
            'agent_entry' => ['scan-attendance'],
            'reader' => ['view-applications'],
            'candidate' => [],
        ];

        foreach ($roles as $name => $perms) {
            $role = Role::firstOrCreate(['name' => $name]);
            $role->syncPermissions($perms);
        }
    }
}
