<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Unique key: slug (deterministic from English name)
     * Idempotent: Uses updateOrCreate with slug to prevent duplicates
     */
    public function run(): void
    {
        DB::transaction(function () {
            $categories = [
                [
                    'name_en' => 'Development Services',
                    'name_ar' => 'خدمات التطوير',
                ],
                [
                    'name_en' => 'Design Services',
                    'name_ar' => 'خدمات التصميم',
                ],
                [
                    'name_en' => 'Marketing Services',
                    'name_ar' => 'خدمات التسويق',
                ],
                [
                    'name_en' => 'Cloud & Infrastructure',
                    'name_ar' => 'السحابة والبنية التحتية',
                ],
                [
                    'name_en' => 'Consulting & Strategy',
                    'name_ar' => 'الاستشارات والاستراتيجية',
                ],
                [
                    'name_en' => 'Support & Maintenance',
                    'name_ar' => 'الدعم والصيانة',
                ],
                [
                    'name_en' => 'Digital Transformation',
                    'name_ar' => 'التحول الرقمي',
                ],
                [
                    'name_en' => 'Analytics & Intelligence',
                    'name_ar' => 'التحليلات والذكاء',
                ],
                [
                    'name_en' => 'Security Services',
                    'name_ar' => 'خدمات الأمان',
                ],
                [
                    'name_en' => 'E-Commerce Solutions',
                    'name_ar' => 'حلول التجارة الإلكترونية',
                ],
            ];

            foreach ($categories as $categoryData) {
                $slug = Str::slug($categoryData['name_en']);

                ServiceCategory::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => [
                            'en' => $categoryData['name_en'],
                            'ar' => $categoryData['name_ar'],
                        ],
                        'slug' => $slug,
                    ]
                );
            }
        });
    }
}
