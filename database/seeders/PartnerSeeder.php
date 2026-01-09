<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Unique key: slug (deterministic from English name)
     * Idempotent: Uses updateOrCreate with slug to prevent duplicates
     * Note: Partner model has no status field, only name and slug
     */
    public function run(): void
    {
        DB::transaction(function () {
            $partners = [
                [
                    'name_en' => 'TechCorp Solutions',
                    'name_ar' => 'حلول تيك كورب',
                ],
                [
                    'name_en' => 'Digital Innovations Ltd',
                    'name_ar' => 'الابتكارات الرقمية المحدودة',
                ],
                [
                    'name_en' => 'Cloud Systems Inc',
                    'name_ar' => 'أنظمة السحابة',
                ],
                [
                    'name_en' => 'Web Masters Agency',
                    'name_ar' => 'وكالة أساتذة الويب',
                ],
                [
                    'name_en' => 'Mobile First Technologies',
                    'name_ar' => 'تقنيات الموبايل أولاً',
                ],
                [
                    'name_en' => 'Data Insights Group',
                    'name_ar' => 'مجموعة رؤى البيانات',
                ],
                [
                    'name_en' => 'Creative Design Studio',
                    'name_ar' => 'استوديو التصميم الإبداعي',
                ],
                [
                    'name_en' => 'Enterprise Solutions Co',
                    'name_ar' => 'شركة حلول المؤسسات',
                ],
            ];

            foreach ($partners as $partnerData) {
                $slug = Str::slug($partnerData['name_en']);
                
                Partner::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => [
                            'en' => $partnerData['name_en'],
                            'ar' => $partnerData['name_ar'],
                        ],
                        'slug' => $slug,
                    ]
                );
            }
        });
    }
}

