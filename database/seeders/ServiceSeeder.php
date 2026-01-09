<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Service;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Unique key: slug (deterministic from English name)
     * Idempotent: Uses updateOrCreate with slug to prevent duplicates
     * Relationships: Attaches tags after upsert using syncWithoutDetaching
     */
    public function run(): void
    {
        DB::transaction(function () {
            $services = [
                [
                    'name_en' => 'Web Development',
                    'name_ar' => 'تطوير الويب',
                    'short_description_en' => 'Custom web applications built with modern technologies',
                    'short_description_ar' => 'تطبيقات ويب مخصصة مبنية بتقنيات حديثة',
                    'description_en' => 'We provide comprehensive web development services including frontend, backend, and full-stack solutions. Our team uses the latest frameworks and technologies to deliver scalable and maintainable web applications.',
                    'description_ar' => 'نقدم خدمات تطوير الويب الشاملة بما في ذلك الواجهة الأمامية والخلفية والحلول الكاملة. يستخدم فريقنا أحدث الأطر والتقنيات لتقديم تطبيقات ويب قابلة للتوسع والصيانة.',
                    'status' => StatusEnum::ACTIVE,
                    'tags' => ['web-development', 'ui-ux-design', 'devops'],
                ],
                [
                    'name_en' => 'Mobile App Development',
                    'name_ar' => 'تطوير تطبيقات الموبايل',
                    'short_description_en' => 'Native and cross-platform mobile applications',
                    'short_description_ar' => 'تطبيقات موبايل أصلية ومتعددة المنصات',
                    'description_en' => 'We develop high-performance mobile applications for iOS and Android platforms. Our solutions include native apps, hybrid apps, and progressive web apps tailored to your business needs.',
                    'description_ar' => 'نطور تطبيقات موبايل عالية الأداء لمنصات iOS و Android. تشمل حلولنا التطبيقات الأصلية والهجينة وتطبيقات الويب التقدمية المصممة خصيصًا لاحتياجات عملك.',
                    'status' => StatusEnum::ACTIVE,
                    'tags' => ['mobile-app', 'ui-ux-design', 'cloud-services'],
                ],
                [
                    'name_en' => 'E-Commerce Solutions',
                    'name_ar' => 'حلول التجارة الإلكترونية',
                    'short_description_en' => 'Complete e-commerce platforms and online stores',
                    'short_description_ar' => 'منصات التجارة الإلكترونية الكاملة والمتاجر عبر الإنترنت',
                    'description_en' => 'Build powerful e-commerce platforms with secure payment gateways, inventory management, and customer relationship tools. We create seamless shopping experiences for your customers.',
                    'description_ar' => 'بناء منصات تجارة إلكترونية قوية مع بوابات دفع آمنة وإدارة المخزون وأدوات علاقات العملاء. نخلق تجارب تسوق سلسة لعملائك.',
                    'status' => StatusEnum::ACTIVE,
                    'tags' => ['e-commerce', 'web-development', 'digital-marketing'],
                ],
                [
                    'name_en' => 'UI/UX Design',
                    'name_ar' => 'تصميم واجهة المستخدم',
                    'short_description_en' => 'User-centered design for exceptional user experiences',
                    'short_description_ar' => 'تصميم يركز على المستخدم لتجارب مستخدم استثنائية',
                    'description_en' => 'Our design team creates intuitive and beautiful user interfaces that enhance user engagement and satisfaction. We follow best practices in user experience design and accessibility.',
                    'description_ar' => 'ينشئ فريق التصميم لدينا واجهات مستخدم بديهية وجميلة تعزز مشاركة المستخدم ورضاه. نتبع أفضل الممارسات في تصميم تجربة المستخدم وإمكانية الوصول.',
                    'status' => StatusEnum::ACTIVE,
                    'tags' => ['ui-ux-design', 'web-development', 'mobile-app'],
                ],
                [
                    'name_en' => 'Cloud Infrastructure',
                    'name_ar' => 'البنية التحتية السحابية',
                    'short_description_en' => 'Scalable cloud solutions and infrastructure management',
                    'short_description_ar' => 'حلول سحابية قابلة للتوسع وإدارة البنية التحتية',
                    'description_en' => 'We help businesses migrate to the cloud and optimize their infrastructure. Our services include cloud architecture design, deployment, monitoring, and maintenance.',
                    'description_ar' => 'نساعد الشركات على الانتقال إلى السحابة وتحسين بنيتها التحتية. تشمل خدماتنا تصميم البنية السحابية والنشر والمراقبة والصيانة.',
                    'status' => StatusEnum::ACTIVE,
                    'tags' => ['cloud-services', 'devops', 'consulting'],
                ],
                [
                    'name_en' => 'Digital Marketing',
                    'name_ar' => 'التسويق الرقمي',
                    'short_description_en' => 'Comprehensive digital marketing strategies and campaigns',
                    'short_description_ar' => 'استراتيجيات وحملات التسويق الرقمي الشاملة',
                    'description_en' => 'Boost your online presence with our digital marketing services. We offer SEO, social media marketing, content marketing, PPC campaigns, and analytics to drive growth.',
                    'description_ar' => 'عزز وجودك على الإنترنت من خلال خدمات التسويق الرقمي لدينا. نقدم تحسين محركات البحث والتسويق عبر وسائل التواصل الاجتماعي والتسويق بالمحتوى وحملات الدفع لكل نقرة والتحليلات لدفع النمو.',
                    'status' => StatusEnum::ACTIVE,
                    'tags' => ['digital-marketing', 'data-analytics', 'consulting'],
                ],
                [
                    'name_en' => 'Data Analytics & BI',
                    'name_ar' => 'تحليل البيانات والذكاء التجاري',
                    'short_description_en' => 'Transform data into actionable business insights',
                    'short_description_ar' => 'تحويل البيانات إلى رؤى عمل قابلة للتنفيذ',
                    'description_en' => 'Unlock the power of your data with advanced analytics and business intelligence solutions. We help you make data-driven decisions with custom dashboards and reports.',
                    'description_ar' => 'اطلق العنان لقوة بياناتك من خلال حلول التحليلات المتقدمة والذكاء التجاري. نساعدك على اتخاذ قرارات مدعومة بالبيانات باستخدام لوحات المعلومات والتقارير المخصصة.',
                    'status' => StatusEnum::ACTIVE,
                    'tags' => ['data-analytics', 'cloud-services', 'consulting'],
                ],
                [
                    'name_en' => 'IT Consulting',
                    'name_ar' => 'استشارات تقنية المعلومات',
                    'short_description_en' => 'Expert technology consulting and strategic planning',
                    'short_description_ar' => 'استشارات تقنية خبيرة والتخطيط الاستراتيجي',
                    'description_en' => 'Get expert guidance on technology strategy, digital transformation, and IT infrastructure. Our consultants help you align technology with your business goals.',
                    'description_ar' => 'احصل على إرشادات خبيرة حول استراتيجية التكنولوجيا والتحول الرقمي والبنية التحتية لتقنية المعلومات. يساعدك مستشارونا على محاذاة التكنولوجيا مع أهداف عملك.',
                    'status' => StatusEnum::INACTIVE,
                    'tags' => ['consulting', 'cloud-services', 'cybersecurity'],
                ],
            ];

            foreach ($services as $serviceData) {
                $slug = Str::slug($serviceData['name_en']);
                
                $service = Service::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => [
                            'en' => $serviceData['name_en'],
                            'ar' => $serviceData['name_ar'],
                        ],
                        'slug' => $slug,
                        'short_description' => [
                            'en' => $serviceData['short_description_en'],
                            'ar' => $serviceData['short_description_ar'],
                        ],
                        'description' => [
                            'en' => $serviceData['description_en'],
                            'ar' => $serviceData['description_ar'],
                        ],
                        'status' => $serviceData['status'],
                    ]
                );

                // Attach tags by slug
                if (isset($serviceData['tags'])) {
                    $tagIds = Tag::whereIn('slug', $serviceData['tags'])->pluck('id');
                    $service->tags()->syncWithoutDetaching($tagIds);
                }
            }
        });
    }
}

