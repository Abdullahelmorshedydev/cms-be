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
                'name' => [
                    'en' => 'Home',
                    'ar' => 'الصفحة الرئيسية'
                ],
                'slug' => 'home'
            ],
            [
                'name' => [
                    'en' => 'About Us',
                    'ar' => 'من نحن'
                ],
                'slug' => 'about-us'
            ],
            [
                'name' => [
                    'en' => 'Contact Us',
                    'ar' => 'اتصل بنا'
                ],
                'slug' => 'contact-us'
            ],
            [
                'name' => [
                    'en' => 'Services',
                    'ar' => 'خدماتنا'
                ],
                'slug' => 'services'
            ],
            [
                'name' => [
                    'en' => 'Projects',
                    'ar' => 'مشاريعنا'
                ],
                'slug' => 'projects'
            ]
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
