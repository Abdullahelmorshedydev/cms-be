<?php

namespace Database\Seeders;

use App\Enums\SectionFieldEnum;
use App\Enums\StatusEnum;
use App\Models\CmsSection;
use App\Models\Page;
use App\Models\SectionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PageSectionsStaticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locales = LaravelLocalization::getSupportedLanguagesKeys();

        $page = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'name' => [
                    'en' => 'Home',
                    'ar' => 'الرئيسية'
                ],
                'status' => StatusEnum::ACTIVE->value
            ]
        );

        $this->createSectionType(
            'hero',
            [
                'en' => 'Hero Section',
                'ar' => 'قسم الرئيسية'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::SUBTITLE->value,
                SectionFieldEnum::IMAGE->value,
                SectionFieldEnum::BUTTON->value
            ]
        );

        $this->createSectionType(
            'our-mission',
            [
                'en' => 'Our Mission',
                'ar' => 'مهمتنا'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::DESCRIPTION->value
            ]
        );

        $this->createSectionType(
            'our-services',
            [
                'en' => 'Our Services',
                'ar' => 'خدماتنا'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::MODEL->value
            ]
        );

        $this->createSectionType(
            'our-works',
            [
                'en' => 'Our Works',
                'ar' => 'اعمالنا'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::MODEL->value
            ]
        );

        $this->createSectionType(
            'our-partners',
            [
                'en' => 'Our Partners',
                'ar' => 'شركاؤنا'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::MODEL->value
            ]
        );

        $sections = [
            [
                'name' => 'hero',
                'type' => 'hero',
                'order' => 1,
                'content' => [
                    'title' => [
                        'en' => 'Tasweek',
                        'ar' => 'تسويق'
                    ],
                    'subtitle' => [
                        'en' => 'Tasweek is a platform that allows you to create and manage your own AI-powered chatbots.',
                        'ar' => 'تسويق هي منصة تتيح لك إنشاء وإدارة روبوتات دردشة مدعومة بالذكاء الاصطناعي الخاصة بك.'
                    ]
                ],
                'button_text' => [
                    'en' => 'Start Your Project',
                    'ar' => 'ابدء المشروع الخاص بك'
                ],
                'button_type' => 'primary',
                'button_data' => '/contact'
            ],
            [
                'name' => 'our-mission',
                'type' => 'our-mission',
                'order' => 2,
                'content' => [
                    'title' => [
                        'en' => 'Our Mission',
                        'ar' => 'مهمتنا'
                    ],
                    'description' => [
                        'en' => 'Tasweek is a platform that allows you to create and manage your own AI-powered chatbots.',
                        'ar' => 'تسويق هي منصة تتيح لك إنشاء وإدارة روبوتات دردشة مدعومة بالذكاء الاصطناعي الخاصة بك.'
                    ]
                ]
            ],
            [
                'name' => 'our-services',
                'type' => 'our-services',
                'order' => '3',
                'content' => [
                    'title' => [
                        'en' => 'Our Services',
                        'ar' => 'خدماتنا'
                    ]
                ]
            ],
            [
                'name' => 'our-works',
                'type' => 'our-works',
                'order' => '4',
                'content' => [
                    'title' => [
                        'en' => 'Our Works',
                        'ar' => 'اعمالنا'
                    ]
                ]
            ],
            [
                'name' => 'our-partners',
                'type' => 'our-partners',
                'order' => '5',
                'content' => [
                    'title' => [
                        'en' => 'Our Partners',
                        'ar' => 'شركاؤنا'
                    ]
                ]
            ]
        ];

        foreach ($sections as $sectionData) {
            $existingSection = CmsSection::where('name', $sectionData['name'])
                ->where('parent_id', $page->id)
                ->where('parent_type', Page::class)
                ->first();

            $sectionDataToSave = [
                'name' => $sectionData['name'],
                'content' => $sectionData['content'],
                'parent_id' => $page->id,
                'parent_type' => Page::class,
                'order' => $sectionData['order'],
                'disabled' => false,
                'button_text' => isset($sectionData['button_text']) ? $sectionData['button_text'] : null,
                'button_type' => $sectionData['button_type'] ?? null,
                'button_data' => $sectionData['button_data'] ?? null
            ];

            $type = SectionType::where('slug', $sectionData['type'])->first();

            if ($existingSection) {
                $sectionDataToSave['created_at'] = $existingSection->created_at;
                $existingSection->update($sectionDataToSave);
                $existingSection->sectionTypes()->sync($type->id);
            } else {
                $sectionDataToSave['created_at'] = now();
                $createdSection = CmsSection::create($sectionDataToSave);
                $createdSection->sectionTypes()->attach($type->id);
            }
        }
    }

    /**
     * Create a section type if it doesn't exist
     */
    private function createSectionType($slug, $name, $fields): void
    {
        SectionType::updateOrCreate(
            [
                'slug' => $slug
            ],
            [
                'name' => $name,
                'fields' => $fields,
                'description' => "Static section type: {$name['en']}"
            ]
        );
    }
}
