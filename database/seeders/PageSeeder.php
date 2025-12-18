<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only create pages if factory exists, otherwise skip
        // Pages can be created manually through the dashboard
        // DB::table('pages')->delete();
        // Page::factory(5)->create();
    }
}
