<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            RoleSeeder::class,
            PageSeeder::class,
            PageSectionsStaticSeeder::class,
            TagSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            PartnerSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
