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
        ################ Permissions ################
        $permissions = Permission::where('guard_name', 'web')->pluck('name');
        $permissionsArray = $permissions->values()->toArray();

        ################ Roles ################
        $superAdminRole = Role::updateOrCreate(
            [
                'name' => 'super-admin',
                'guard_name' => 'web',
            ],
            [
                'display_name' => json_encode([
                    'en' => 'Super Admin',
                    'ar' => 'سوبر مسؤول'
                ]),
                'guard_name' => 'web',
            ]
        );
        $adminRole = Role::updateOrCreate(
            [
                'name' => 'admin',
                'guard_name' => 'web',
            ],
            [
                'display_name' => json_encode([
                    'en' => 'Admin',
                    'ar' => 'مسؤول'
                ]),
                'guard_name' => 'web',
            ]
        );

        ################ Give Permissions to Roles ################
        $superAdminRole->givePermissionTo($permissionsArray);
        $adminRole->givePermissionTo($permissionsArray);

        ################ Assign Roles to Users ################
        $superAdmin = User::firstWhere('email', 'super-admin@tasweek.com');
        if ($superAdmin)
            $superAdmin->assignRole([$superAdminRole]);

        $admin = User::firstWhere('email', 'admin@tasweek.com');
        if ($admin)
            $admin->assignRole([$adminRole]);
    }
}
