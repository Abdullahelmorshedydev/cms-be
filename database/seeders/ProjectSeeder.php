<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Unique key: slug (deterministic from English name)
     * Idempotent: Uses updateOrCreate with slug to prevent duplicates
     * Note: Project model has no relationships defined, only standalone entity
     */
    public function run(): void
    {
        DB::transaction(function () {
            $projects = [
                [
                    'name_en' => 'E-Commerce Platform Redesign',
                    'name_ar' => 'إعادة تصميم منصة التجارة الإلكترونية',
                    'short_description_en' => 'Complete redesign of a major e-commerce platform',
                    'short_description_ar' => 'إعادة تصميم كاملة لمنصة تجارة إلكترونية رئيسية',
                    'description_en' => 'We redesigned and rebuilt a comprehensive e-commerce platform with improved user experience, better performance, and enhanced security features. The project included migration of existing data and integration with multiple payment gateways.',
                    'description_ar' => 'أعدنا تصميم وبناء منصة تجارة إلكترونية شاملة مع تحسين تجربة المستخدم وأداء أفضل وميزات أمان محسنة. شمل المشروع نقل البيانات الموجودة والتكامل مع بوابات دفع متعددة.',
                    'url' => 'https://example.com/project/ecommerce-redesign',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Mobile Banking Application',
                    'name_ar' => 'تطبيق البنك المحمول',
                    'short_description_en' => 'Secure mobile banking app for iOS and Android',
                    'short_description_ar' => 'تطبيق بنكي محمول آمن لـ iOS و Android',
                    'description_en' => 'Developed a secure and user-friendly mobile banking application with biometric authentication, real-time transaction monitoring, and comprehensive financial management features. The app supports multiple languages and currencies.',
                    'description_ar' => 'طورنا تطبيق بنكي محمول آمن وسهل الاستخدام مع مصادقة بيومترية ومراقبة المعاملات في الوقت الفعلي وميزات إدارة مالية شاملة. يدعم التطبيق لغات وعملات متعددة.',
                    'url' => 'https://example.com/project/mobile-banking',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Healthcare Management System',
                    'name_ar' => 'نظام إدارة الرعاية الصحية',
                    'short_description_en' => 'Comprehensive healthcare management and patient portal',
                    'short_description_ar' => 'نظام إدارة رعاية صحية شامل وبوابة للمرضى',
                    'description_en' => 'Built a complete healthcare management system that includes patient records management, appointment scheduling, billing, and telemedicine capabilities. The system is HIPAA compliant and integrates with various medical devices.',
                    'description_ar' => 'بنينا نظام إدارة رعاية صحية كاملاً يتضمن إدارة سجلات المرضى وجدولة المواعيد والفواتير وقدرات الطب عن بُعد. النظام متوافق مع HIPAA ويتكامل مع أجهزة طبية مختلفة.',
                    'url' => 'https://example.com/project/healthcare-system',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Real Estate Portal',
                    'name_ar' => 'بوابة العقارات',
                    'short_description_en' => 'Modern real estate listing and management platform',
                    'short_description_ar' => 'منصة حديثة لإدراج وإدارة العقارات',
                    'description_en' => 'Created a feature-rich real estate portal with advanced search filters, virtual property tours, mortgage calculators, and agent management tools. The platform supports multiple property types and locations.',
                    'description_ar' => 'أنشأنا بوابة عقارات غنية بالميزات مع مرشحات بحث متقدمة وجولات عقارية افتراضية وآلات حاسبة للرهن العقاري وأدوات إدارة الوكلاء. تدعم المنصة أنواع عقارات ومواقع متعددة.',
                    'url' => 'https://example.com/project/real-estate-portal',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Learning Management System',
                    'name_ar' => 'نظام إدارة التعلم',
                    'short_description_en' => 'Comprehensive LMS for online education',
                    'short_description_ar' => 'نظام إدارة تعلم شامل للتعليم عبر الإنترنت',
                    'description_en' => 'Developed a scalable learning management system with course creation tools, student progress tracking, assessments, video conferencing integration, and certificate generation. Supports multiple learning formats and languages.',
                    'description_ar' => 'طورنا نظام إدارة تعلم قابل للتوسع مع أدوات إنشاء الدورات وتتبع تقدم الطلاب والتقييمات وتكامل مؤتمرات الفيديو وإنشاء الشهادات. يدعم تنسيقات ولغات تعلم متعددة.',
                    'url' => 'https://example.com/project/lms-platform',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Food Delivery Application',
                    'name_ar' => 'تطبيق توصيل الطعام',
                    'short_description_en' => 'Multi-restaurant food delivery platform',
                    'short_description_ar' => 'منصة توصيل طعام متعددة المطاعم',
                    'description_en' => 'Built a comprehensive food delivery application connecting customers with restaurants. Features include real-time order tracking, multiple payment options, restaurant management dashboard, and delivery driver app.',
                    'description_ar' => 'بنينا تطبيق توصيل طعام شامل يربط العملاء بالمطاعم. تشمل الميزات تتبع الطلبات في الوقت الفعلي وخيارات دفع متعددة ولوحة تحكم إدارة المطاعم وتطبيق سائق التوصيل.',
                    'url' => 'https://example.com/project/food-delivery',
                    'status' => StatusEnum::ACTIVE,
                ],
                [
                    'name_en' => 'Corporate Intranet Portal',
                    'name_ar' => 'بوابة الإنترانت المؤسسية',
                    'short_description_en' => 'Internal communication and collaboration platform',
                    'short_description_ar' => 'منصة التواصل والتعاون الداخلي',
                    'description_en' => 'Created an enterprise intranet portal with document management, team collaboration tools, internal messaging, project tracking, and company-wide announcements. Integrated with existing HR and ERP systems.',
                    'description_ar' => 'أنشأنا بوابة إنترانت مؤسسية مع إدارة المستندات وأدوات تعاون الفريق والمراسلة الداخلية وتتبع المشاريع والإعلانات على مستوى الشركة. متكامل مع أنظمة الموارد البشرية و ERP الموجودة.',
                    'url' => 'https://example.com/project/corporate-intranet',
                    'status' => StatusEnum::INACTIVE,
                ],
                [
                    'name_en' => 'IoT Dashboard Platform',
                    'name_ar' => 'منصة لوحة تحكم إنترنت الأشياء',
                    'short_description_en' => 'Real-time monitoring and control for IoT devices',
                    'short_description_ar' => 'المراقبة والتحكم في الوقت الفعلي لأجهزة إنترنت الأشياء',
                    'description_en' => 'Developed an IoT dashboard platform for monitoring and controlling connected devices. Features include real-time data visualization, alert management, device configuration, and historical data analysis. Supports multiple IoT protocols.',
                    'description_ar' => 'طورنا منصة لوحة تحكم إنترنت الأشياء لمراقبة والتحكم في الأجهزة المتصلة. تشمل الميزات تصور البيانات في الوقت الفعلي وإدارة التنبيهات وتكوين الأجهزة وتحليل البيانات التاريخية. يدعم بروتوكولات إنترنت الأشياء المتعددة.',
                    'url' => 'https://example.com/project/iot-dashboard',
                    'status' => StatusEnum::ACTIVE,
                ],
            ];

            foreach ($projects as $projectData) {
                $slug = Str::slug($projectData['name_en']);

                Project::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => [
                            'en' => $projectData['name_en'],
                            'ar' => $projectData['name_ar'],
                        ],
                        'slug' => $slug,
                        'short_description' => [
                            'en' => $projectData['short_description_en'],
                            'ar' => $projectData['short_description_ar'],
                        ],
                        'description' => [
                            'en' => $projectData['description_en'],
                            'ar' => $projectData['description_ar'],
                        ],
                        'url' => $projectData['url'],
                        'status' => $projectData['status'],
                    ]
                );
            }
        });
    }
}
