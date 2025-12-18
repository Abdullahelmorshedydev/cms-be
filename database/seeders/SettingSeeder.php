<?php

namespace Database\Seeders;

use App\Enums\SettingGroupEnum;
use App\Enums\SettingTypeEnum;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siteName = Setting::firstOrCreate([
            'key' => 'site_name',
        ], [
            'key' => 'site_name',
            'label' => [
                'en' => 'Site Name',
                'ar' => 'اسم الموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Name',
                'ar' => 'اسم الموقع',
            ],
            'group' => SettingGroupEnum::MAIN,
        ]);

        $siteDescription = Setting::firstOrCreate([
            'key' => 'site_description',
        ], [
            'key' => 'site_description',
            'label' => [
                'en' => 'Site Description',
                'ar' => 'وصف الموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Description',
                'ar' => 'وصف الموقع',
            ],
            'group' => SettingGroupEnum::MAIN,
        ]);

        $siteLogo = Setting::firstOrCreate([
            'key' => 'site_logo',
        ], [
            'key' => 'site_logo',
            'label' => [
                'en' => 'Site Logo',
                'ar' => 'شعار الموقع',
            ],
            'type' => SettingTypeEnum::IMAGE->value,
            'group' => SettingGroupEnum::MAIN,
        ]);

        $siteFavicon = Setting::firstOrCreate([
            'key' => 'site_favicon',
        ], [
            'key' => 'site_favicon',
            'label' => [
                'en' => 'Site Favicon',
                'ar' => 'شعار الموقع',
            ],
            'type' => SettingTypeEnum::IMAGE->value,
            'group' => SettingGroupEnum::MAIN,
        ]);

        $siteEmail = Setting::firstOrCreate([
            'key' => 'site_email',
        ], [
            'key' => 'site_email',
            'label' => [
                'en' => 'Site Email',
                'ar' => 'البريد الالكتروني للموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Email',
                'ar' => 'البريد الالكتروني للموقع',
            ],
            'group' => SettingGroupEnum::SOCIAL,
        ]);

        $siteFacebook = Setting::firstOrCreate([
            'key' => 'site_facebook',
        ], [
            'key' => 'site_facebook',
            'label' => [
                'en' => 'Site Facebook',
                'ar' => 'فيسبوك للموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Facebook',
                'ar' => 'فيسبوك للموقع',
            ],
            'group' => SettingGroupEnum::SOCIAL,
        ]);

        $siteTwitter = Setting::firstOrCreate([
            'key' => 'site_twitter',
        ], [
            'key' => 'site_twitter',
            'label' => [
                'en' => 'Site Twitter',
                'ar' => 'تويتر للموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Twitter',
                'ar' => 'تويتر للموقع',
            ],
            'group' => SettingGroupEnum::SOCIAL,
        ]);

        $siteInstagram = Setting::firstOrCreate([
            'key' => 'site_instagram',
        ], [
            'key' => 'site_instagram',
            'label' => [
                'en' => 'Site Instagram',
                'ar' => 'انستجرام للموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Instagram',
                'ar' => 'انستجرام للموقع',
            ],
            'group' => SettingGroupEnum::SOCIAL,
        ]);

        $siteWhatsapp = Setting::firstOrCreate([
            'key' => 'site_whatsapp',
        ], [
            'key' => 'site_whatsapp',
            'label' => [
                'en' => 'Site Whatsapp',
                'ar' => 'واتساب للموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Whatsapp',
                'ar' => 'واتساب للموقع',
            ],
            'group' => SettingGroupEnum::SOCIAL,
        ]);

        $siteYoutube = Setting::firstOrCreate([
            'key' => 'site_youtube',
        ], [
            'key' => 'site_youtube',
            'label' => [
                'en' => 'Site Youtube',
                'ar' => 'يوتيوب للموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Youtube',
                'ar' => 'يوتيوب للموقع',
            ],
            'group' => SettingGroupEnum::SOCIAL,
        ]);

        $siteGoogle = Setting::firstOrCreate([
            'key' => 'site_google',
        ], [
            'key' => 'site_google',
            'label' => [
                'en' => 'Site Google',
                'ar' => 'جوجل للموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Google',
                'ar' => 'جوجل للموقع',
            ],
            'group' => SettingGroupEnum::SOCIAL,
        ]);

        $siteLinkedin = Setting::firstOrCreate([
            'key' => 'site_linkedin',
        ], [
            'key' => 'site_linkedin',
            'label' => [
                'en' => 'Site Linkedin',
                'ar' => 'لينكدان للموقع',
            ],
            'type' => SettingTypeEnum::TEXT->value,
            'value' => [
                'en' => 'Site Linkedin',
                'ar' => 'لينكدان للموقع',
            ],
            'group' => SettingGroupEnum::SOCIAL,
        ]);
    }
}
