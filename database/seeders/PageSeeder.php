<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'name' => 'Home',
                'slug' => 'home'
            ],
            [
                'name' => 'About Us',
                'slug' => 'about-us',
            ],
            [
                'name' => 'Contact Us',
                'slug' => 'contact-us',
            ],
            [
                'name' => 'Privacy Policy',
                'slug' => 'privacy-policy',
            ],
            [
                'name' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
            ],
            [
                'name' => 'FAQ',
                'slug' => 'faq',
            ],
            [
                'name' => 'Help',
                'slug' => 'help',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                [
                    'slug' => $page['slug']
                ],
                $page
            );
        }
    }
}
