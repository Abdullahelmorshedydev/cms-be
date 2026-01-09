<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
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
            $tags = [
                [
                    'name_en' => 'Web Development',
                    'name_ar' => 'تطوير الويب',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Mobile App',
                    'name_ar' => 'تطبيق موبايل',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'E-Commerce',
                    'name_ar' => 'تجارة إلكترونية',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'UI/UX Design',
                    'name_ar' => 'تصميم واجهة المستخدم',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Cloud Services',
                    'name_ar' => 'خدمات السحابة',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Digital Marketing',
                    'name_ar' => 'التسويق الرقمي',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Data Analytics',
                    'name_ar' => 'تحليل البيانات',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'AI & Machine Learning',
                    'name_ar' => 'الذكاء الاصطناعي والتعلم الآلي',
                    'status' => StatusEnum::INACTIVE,
                ],
                [
                    'name_en' => 'Cybersecurity',
                    'name_ar' => 'الأمن السيبراني',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Blockchain',
                    'name_ar' => 'بلوك تشين',
                    'status' => StatusEnum::INACTIVE,
                ],
                [
                    'name_en' => 'DevOps',
                    'name_ar' => 'ديف أوبس',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Consulting',
                    'name_ar' => 'استشارات',
                    'status' => StatusEnum::ACTIVE,
                ],
            ];

            foreach ($tags as $tagData) {
                $slug = Str::slug($tagData['name_en']);
                
                Tag::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => [
                            'en' => $tagData['name_en'],
                            'ar' => $tagData['name_ar'],
                        ],
                        'slug' => $slug,
                        'status' => $tagData['status'],
                    ]
                );
            }
        });
    }
}

