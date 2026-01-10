<?php

namespace Database\Seeders;

use App\Enums\SectionFieldEnum;
use App\Models\CmsSection;
use App\Models\Page;
use App\Models\SectionType;
use Illuminate\Database\Seeder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PageSectionsStaticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locales = LaravelLocalization::getSupportedLanguagesKeys();

        $this->createSectionType(
            'title-subtitle-image-button',
            [
                'en' => 'Title, Subtitle, Image & Button',
                'ar' => 'عنوان و تصنيف و صورة و زر'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::SUBTITLE->value,
                SectionFieldEnum::IMAGE->value,
                SectionFieldEnum::BUTTON->value
            ]
        );

        $this->createSectionType(
            'title-description',
            [
                'en' => 'Title & Description',
                'ar' => 'عنوان و وصف'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::DESCRIPTION->value
            ]
        );

        $this->createSectionType(
            'title-model',
            [
                'en' => 'Title & Model',
                'ar' => 'عنوان و موديل'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::MODEL->value
            ]
        );

        $this->createSectionType(
            'title',
            [
                'en' => 'Title',
                'ar' => 'عنوان'
            ],
            [
                SectionFieldEnum::TITLE->value
            ]
        );

        $this->createSectionType(
            'title-image',
            [
                'en' => 'Title & Image',
                'ar' => 'عنوان و صورة'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::DESCRIPTION->value,
                SectionFieldEnum::IMAGE->value
            ]
        );

        $this->createSectionType(
            'title-description-image',
            [
                'en' => 'Title, Description & Image',
                'ar' => 'عنوان و وصف و صورة'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::DESCRIPTION->value,
                SectionFieldEnum::IMAGE->value
            ]
        );

        $this->createSectionType(
            'title-subtitle-model-image',
            [
                'en' => 'Title, Subtitle, Model & Image',
                'ar' => 'عنوان و تصنيف و موديل و صورة'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::SUBTITLE->value,
                SectionFieldEnum::MODEL->value,
                SectionFieldEnum::IMAGE->value
            ]
        );

        $this->createSectionType(
            'title-subtitle',
            [
                'en' => 'Title & Subtitle',
                'ar' => 'عنوان و تصنيف'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::SUBTITLE->value
            ]
        );

        $this->createSectionType(
            'video',
            [
                'en' => 'Video',
                'ar' => 'فيديو'
            ],
            [
                SectionFieldEnum::VIDEO->value
            ]
        );

        $this->createSectionType(
            'seo',
            [
                'en' => 'SEO',
                'ar' => 'SEO'
            ],
            [
                SectionFieldEnum::TITLE->value,
                SectionFieldEnum::SUBTITLE->value,
                SectionFieldEnum::DESCRIPTION->value
            ]
        );

        $sections = [
            [
                'name' => 'hero',
                'page_slug' => 'home',
                'type' => 'title-subtitle-image-button',
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
                'button_data' => '/contact-us'
            ],
            [
                'name' => 'our-mission',
                'page_slug' => 'home',
                'type' => 'title-description',
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
                'page_slug' => 'home',
                'type' => 'title-model',
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
                'page_slug' => 'home',
                'type' => 'title-model',
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
                'page_slug' => 'home',
                'type' => 'title-model',
                'order' => '5',
                'content' => [
                    'title' => [
                        'en' => 'Our Partners',
                        'ar' => 'شركاؤنا'
                    ]
                ]
            ],
            [
                'name' => 'seo',
                'page_slug' => 'home',
                'type' => 'seo',
                'order' => '6',
                'content' => [
                    'title' => [
                        'en' => 'home seo',
                        'ar' => 'hoe seo'
                    ],
                    'subtitle' => [
                        'en' => 'home seo',
                        'ar' => 'hoe seo'
                    ],
                    'description' => [
                        'en' => 'home seo',
                        'ar' => 'hoe seo'
                    ],
                ]
            ],
            [
                'name' => 'hero',
                'page_slug' => 'about-us',
                'type' => 'title',
                'order' => '1',
                'content' => [
                    'title' => [
                        'en' => 'Proudly signing every piece',
                        'ar' => 'وحيدا تتوقف على كل قطعة'
                    ]
                ]
            ],
            [
                'name' => 'our-mission',
                'page_slug' => 'about-us',
                'type' => 'title-description-image',
                'order' => '2',
                'content' => [
                    'title' => [
                        'en' => 'Our Mission',
                        'ar' => 'مهمتنا'
                    ],
                    'description' => [
                        'en' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Provident nemo tempore omnis. Molestiae repellendus animi omnis quam eveniet fugiat corrupti fugit totam commodi explicabo? Minus repudiandae quis animi aliquam eveniet.',
                        'ar' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Provident nemo tempore omnis. Molestiae repellendus animi omnis quam eveniet fugiat corrupti fugit totam commodi explicabo? Minus repudiandae quis animi aliquam eveniet.'
                    ]
                ]
            ],
            [
                'name' => 'our-approach',
                'page_slug' => 'about-us',
                'type' => 'title-description-image',
                'order' => '3',
                'content' => [
                    'title' => [
                        'en' => 'Our Approach',
                        'ar' => 'منطقتنا'
                    ],
                    'description' => [
                        'en' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Provident nemo tempore omnis. Molestiae repellendus animi omnis quam eveniet fugiat corrupti fugit totam commodi explicabo? Minus repudiandae quis animi aliquam eveniet.',
                        'ar' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Provident nemo tempore omnis. Molestiae repellendus animi omnis quam eveniet fugiat corrupti fugit totam commodi explicabo? Minus repudiandae quis animi aliquam eveniet.'
                    ]
                ]
            ],
            [
                'name' => 'video',
                'page_slug' => 'about-us',
                'type' => 'video',
                'order' => '4'
            ],
            [
                'name' => 'seo',
                'page_slug' => 'about-us',
                'type' => 'seo',
                'order' => '5',
                'content' => [
                    'title' => [
                        'en' => 'about-us seo',
                        'ar' => 'about-us seo'
                    ],
                    'subtitle' => [
                        'en' => 'about-us seo',
                        'ar' => 'about-us seo'
                    ],
                    'description' => [
                        'en' => 'about-us seo',
                        'ar' => 'about-us seo'
                    ],
                ]
            ],
            [
                'name' => 'form',
                'page_slug' => 'contact-us',
                'type' => 'title',
                'order' => '1',
                'content' => [
                    'title' => [
                        'en' => 'Good Things happen when you say hey',
                        'ar' => 'كل شيء يحدث عندما يقولون لك حي'
                    ]
                ]
            ],
            [
                'name' => 'seo',
                'page_slug' => 'contact-us',
                'type' => 'seo',
                'order' => '2',
                'content' => [
                    'title' => [
                        'en' => 'contact-us seo',
                        'ar' => 'contact-us seo'
                    ],
                    'subtitle' => [
                        'en' => 'contact-us seo',
                        'ar' => 'contact-us seo'
                    ],
                    'description' => [
                        'en' => 'contact-us seo',
                        'ar' => 'contact-us seo'
                    ],
                ]
            ],
            [
                'name' => 'hero',
                'page_slug' => 'services',
                'type' => 'title-image',
                'order' => '1',
                'content' => [
                    'title' => [
                        'en' => 'Welcome to the digital renaissance.',
                        'ar' => 'مرحبا بك في التحول الرقمي'
                    ]
                ]
            ],
            [
                'name' => 'discover-more',
                'page_slug' => 'services',
                'type' => 'title-subtitle',
                'order' => '2',
                'content' => [
                    'title' => [
                        'en' => 'Discover More',
                        'ar' => 'اكتشف المزيد'
                    ],
                    'subtitle' => [
                        'en' => 'Crafting the future of websites with enjoyably-creative and technologically-advanced design and development.',
                        'ar' => 'تصميم وتطوير المواقع الخلفية للمستقبل مع التصميم المميز والتقنية المتقدمة.'
                    ]
                ]
            ],
            [
                'name' => 'service-details',
                'page_slug' => 'services',
                'type' => 'title-subtitle',
                'order' => '3',
                'content' => [
                    'title' => [
                        'en' => 'Service Details',
                        'ar' => 'تفاصيل الخدمة'
                    ],
                    'subtitle' => [
                        'en' => 'Crafting the future of websites with enjoyably-creative and technologically-advanced design and development.',
                        'ar' => 'تصميم وتطوير المواقع الخلفية للمستقبل مع التصميم المميز والتقنية المتقدمة.'
                    ]
                ],
                'sub_sections' => [
                    [
                        'name' => 'service-details-1',
                        'type' => 'title-subtitle-image',
                        'order' => '1',
                        'content' => [
                            'title' => [
                                'en' => 'Content & Functionality.',
                                'ar' => 'المحتوى والوظائف.'
                            ],
                            'subtitle' => [
                                'en' => 'By finding natural breakpoints within the content and prioritising functionality, we\'ll make sure your site responds seamlessly on any device.',
                                'ar' => 'بالبحث عن انقطاعات طبيعية في المحتوى وترتيب الوظائف، سوف نضمن لك ان تتواصل مع الموقع بطبيعة في اي جهاز.'
                            ]
                        ]
                    ],
                    [
                        'name' => 'service-details-2',
                        'type' => 'title-subtitle-image',
                        'order' => '2',
                        'content' => [
                            'title' => [
                                'en' => 'Content & Functionality.',
                                'ar' => 'المحتوى والوظائف.'
                            ],
                            'subtitle' => [
                                'en' => 'By finding natural breakpoints within the content and prioritising functionality, we\'ll make sure your site responds seamlessly on any device.',
                                'ar' => 'بالبحث عن انقطاعات طبيعية في المحتوى وترتيب الوظائف، سوف نضمن لك ان تتواصل مع الموقع بطبيعة في اي جهاز.'
                            ]
                        ]
                    ],
                    [
                        'name' => 'service-details-3',
                        'type' => 'title-subtitle-image',
                        'order' => '3',
                        'content' => [
                            'title' => [
                                'en' => 'Content & Functionality.',
                                'ar' => 'المحتوى والوظائف.'
                            ],
                            'subtitle' => [
                                'en' => 'By finding natural breakpoints within the content and prioritising functionality, we\'ll make sure your site responds seamlessly on any device.',
                                'ar' => 'بالبحث عن انقطاعات طبيعية في المحتوى وترتيب الوظائف، سوف نضمن لك ان تتواصل مع الموقع بطبيعة في اي جهاز.'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'seo',
                'page_slug' => 'services',
                'type' => 'seo',
                'order' => '4',
                'content' => [
                    'title' => [
                        'en' => 'services seo',
                        'ar' => 'services seo'
                    ],
                    'subtitle' => [
                        'en' => 'services seo',
                        'ar' => 'services seo'
                    ],
                    'description' => [
                        'en' => 'services seo',
                        'ar' => 'services seo'
                    ],
                ]
            ],
            [
                'name' => 'hero',
                'page_slug' => 'projects',
                'type' => 'title-image',
                'order' => '1',
                'content' => [
                    'title' => [
                        'en' => 'Check out some of our work',
                        'ar' => 'تحقق من بعض عملاتنا'
                    ]
                ]
            ],
            [
                'name' => 'seo',
                'page_slug' => 'projects',
                'type' => 'seo',
                'order' => '2',
                'content' => [
                    'title' => [
                        'en' => 'projects seo',
                        'ar' => 'projects seo'
                    ],
                    'subtitle' => [
                        'en' => 'projects seo',
                        'ar' => 'projects seo'
                    ],
                    'description' => [
                        'en' => 'projects seo',
                        'ar' => 'projects seo'
                    ],
                ]
            ]
        ];

        foreach ($sections as $sectionData) {
            $page = Page::where('slug', $sectionData['page_slug'])->first();
            if (!$page)
                continue;
            $existingSection = CmsSection::where('name', $sectionData['name'])
                ->where('parent_id', $page->id)
                ->where('parent_type', Page::class)
                ->first();

            $sectionDataToSave = [
                'name' => $sectionData['name'],
                'content' => $sectionData['content'] ?? null,
                'parent_id' => $page->id,
                'parent_type' => Page::class,
                'order' => $sectionData['order'],
                'disabled' => false,
                'button_text' => isset($sectionData['button_text']) ? $sectionData['button_text'] : null,
                'button_type' => $sectionData['button_type'] ?? null,
                'button_data' => $sectionData['button_data'] ?? null
            ];

            $type = SectionType::where('slug', $sectionData['type'])->first();
            if (!$type)
                continue;

            if ($existingSection) {
                $sectionDataToSave['created_at'] = $existingSection->created_at;
                $existingSection->update($sectionDataToSave);
                $existingSection->sectionTypes()->sync($type->id);
                foreach ($sectionData['sub_sections'] ?? [] as $subSectionData) {
                    $subSectionDataToSave = [
                        'name' => $subSectionData['name'],
                        'content' => $subSectionData['content'],
                        'parent_id' => $existingSection->id,
                        'parent_type' => CmsSection::class,
                        'order' => $subSectionData['order'],
                        'disabled' => false,
                        'button_text' => isset($subSectionData['button_text']) ? $subSectionData['button_text'] : null,
                        'button_type' => $subSectionData['button_type'] ?? null,
                        'button_data' => $subSectionData['button_data'] ?? null
                    ];
                    $subSection = CmsSection::updateOrCreate(
                        [
                            'name' => $subSectionData['name'],
                            'parent_type' => CmsSection::class,
                            'parent_id' => $existingSection->id
                        ],
                        $subSectionDataToSave
                    );
                    $subSection->sectionTypes()->sync($type->id);
                }
            } else {
                $sectionDataToSave['created_at'] = now();
                $createdSection = CmsSection::create($sectionDataToSave);
                $createdSection->sectionTypes()->sync($type->id);
                foreach ($sectionData['sub_sections'] ?? [] as $subSectionData) {
                    $subSectionDataToSave = [
                        'name' => $subSectionData['name'],
                        'content' => $subSectionData['content'],
                        'parent_id' => $createdSection->id,
                        'parent_type' => CmsSection::class,
                        'order' => $subSectionData['order'],
                        'disabled' => false,
                        'button_text' => isset($subSectionData['button_text']) ? $subSectionData['button_text'] : null,
                        'button_type' => $subSectionData['button_type'] ?? null,
                        'button_data' => $subSectionData['button_data'] ?? null
                    ];
                    $subSection = CmsSection::updateOrCreate(
                        [
                            'name' => $subSectionData['name'],
                            'parent_type' => CmsSection::class,
                            'parent_id' => $createdSection->id
                        ],
                        $subSectionDataToSave
                    );
                    $subSection->sectionTypes()->sync($type->id);
                }
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
                'description' => [
                    'en' => "Static section type: {$name['en']}",
                    'ar' => "نوع القسم الثابت: {$name['en']}"
                ]
            ]
        );
    }
}
