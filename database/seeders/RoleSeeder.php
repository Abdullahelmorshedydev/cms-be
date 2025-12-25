<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesPermissions = config('roles_permissions');

        foreach ($rolesPermissions as $guard => $roles) {
            $existingPermissions = Permission::where('guard_name', $guard)
                ->pluck('name')
                ->toArray();

            foreach ($roles as $roleName => $roleData) {
                $role = Role::updateOrCreate(
                    [
                        'name' => $roleName,
                        'guard_name' => $guard,
                    ],
                    [
                        'display_name' => json_encode($roleData['display_name']),
                    ]
                );

                if ($roleData['permissions'] === '*') {
                    $role->syncPermissions($existingPermissions);
                    continue;
                }

                $permissions = [];

                foreach ($roleData['permissions'] as $module => $actions) {
                    foreach ($actions as $action) {
                        $permissions[] = "{$module}.{$action}";
                    }
                }

                $permissions = array_values(array_intersect(
                    $permissions,
                    $existingPermissions
                ));

                $role->syncPermissions($permissions);
            }
        }

        ################ Assign Roles to Users ################
        foreach (config('users_roles') as $userEmail => $roles) {
            User::firstWhere('email', $userEmail)?->assignRole($roles);
        }
    }
}
