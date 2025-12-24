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
                'slug' => 'about-us',
            ],
            [
                'name' => [
                    'en' => 'Contact Us',
                    'ar' => 'اتصل بنا'
                ],
                'slug' => 'contact-us',
            ],
            [
                'name' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية'
                ],
                'slug' => 'privacy-policy',
            ],
            [
                'name' => [
                    'en' => 'Terms & Conditions',
                    'ar' => 'الشروط والاحكام'
                ],
                'slug' => 'terms-and-conditions',
            ],
            [
                'name' => [
                    'en' => 'FAQ',
                    'ar' => 'الاسئلة الشائعة'
                ],
                'slug' => 'faq',
            ],
            [
                'name' => [
                    'en' => 'Help',
                    'ar' => 'المساعدة'
                ],
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
