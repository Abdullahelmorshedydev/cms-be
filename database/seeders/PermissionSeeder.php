<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = config('permissions');
        $en = trans('permissions', [], 'en');
        $ar = trans('permissions', [], 'ar');

        foreach ($permissions as $group => $actions) {
            foreach ($actions as $action) {
                $name = "$group.$action";

                $displayName = [
                    'en' => $en[$group]['actions'][$action] ?? ucfirst($action),
                    'ar' => $ar[$group]['actions'][$action] ?? ucfirst($action),
                ];

                $displayGroupName = [
                    'en' => $en[$group]['name'] ?? ucfirst($group),
                    'ar' => $ar[$group]['name'] ?? ucfirst($group),
                ];

                Permission::updateOrCreate(
                    [
                        'name' => $name,
                        'group' => $group,
                        'guard_name' => 'web'
                    ],
                    [
                        'name' => $name,
                        'display_name' => json_encode($displayName),
                        'group' => $group,
                        'display_group_name' => json_encode($displayGroupName),
                        'guard_name' => 'web',
                    ]
                );
            }
        }
    }
}
