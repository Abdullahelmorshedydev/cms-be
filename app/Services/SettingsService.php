<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected $cachePrefix = 'setting:';
    protected $cacheTTL = 3600; // 1 hour

    /**
     * Get setting value with caching
     */
    public function get(string $key, $default = null, ?string $group = null)
    {
        $cacheKey = $this->cachePrefix . ($group ? "{$group}:{$key}" : $key);

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($key, $default, $group) {
            $query = Setting::where('key', $key);

            if ($group) {
                $query->where('group', $group);
            }

            $setting = $query->first();

            if (!$setting) {
                return $default;
            }

            $settingType = $setting->type;
            if (is_object($settingType) && method_exists($settingType, 'value')) {
                $settingType = $settingType->value;
            }
            return $this->castValue($setting->value, (string) $settingType);
        });
    }

    /**
     * Set setting value
     */
    public function set(string $key, $value, ?string $group = null, ?string $type = null): Setting
    {
        $type = $type ?? $this->detectType($value);

        $setting = Setting::updateOrCreate(
            [
                'key' => $key,
                'group' => $group,
            ],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
            ]
        );

        // Clear cache
        $cacheKey = $this->cachePrefix . ($group ? "{$group}:{$key}" : $key);
        Cache::forget($cacheKey);

        return $setting;
    }

    /**
     * Get all settings by group
     */
    public function getByGroup(string $group): array
    {
        $cacheKey = $this->cachePrefix . "group:{$group}";

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($group) {
            return Setting::where('group', $group)
                ->get()
                ->mapWithKeys(function ($setting) {
                    $settingType = $setting->type;
                    if (is_object($settingType) && method_exists($settingType, 'value')) {
                        $settingType = $settingType->value;
                    }
                    return [$setting->key => $this->castValue($setting->value, $settingType)];
                })
                ->toArray();
        });
    }

    /**
     * Get all settings grouped
     */
    public function getAllGrouped(): array
    {
        return Setting::all()
            ->groupBy('group')
            ->map(function ($settings) {
                return $settings->mapWithKeys(function ($setting) {
                    $settingType = $setting->type;
                    if (is_object($settingType) && method_exists($settingType, 'value')) {
                        $settingType = $settingType->value;
                    }
                    return [$setting->key => [
                        'value' => $this->castValue($setting->value, $settingType),
                        'type' => (string) $settingType,
                        'label' => $setting->label,
                        'description' => $setting->description,
                    ]];
                });
            })
            ->toArray();
    }

    /**
     * Check if module is enabled
     */
    public function isModuleEnabled(string $module): bool
    {
        return (bool) $this->get("module_{$module}_enabled", false, 'modules');
    }

    /**
     * Check if feature is enabled
     */
    public function isFeatureEnabled(string $feature): bool
    {
        return (bool) $this->get("feature_{$feature}_enabled", false, 'features');
    }

    /**
     * Get course type settings
     */
    public function getCourseTypeSettings(): array
    {
        return [
            'online_enabled' => $this->get('course_online_enabled', true, 'courses'),
            'offline_enabled' => $this->get('course_offline_enabled', true, 'courses'),
            'hybrid_enabled' => $this->get('course_hybrid_enabled', true, 'courses'),
            'recorded_enabled' => $this->get('course_recorded_enabled', true, 'courses'),
        ];
    }

    /**
     * Get automation source settings
     */
    public function getAutomationSources(): array
    {
        return [
            'facebook' => [
                'enabled' => $this->get('facebook_enabled', false, 'automation_sources'),
                'app_id' => $this->get('facebook_app_id', null, 'automation_sources'),
                'app_secret' => $this->get('facebook_app_secret', null, 'automation_sources'),
                'page_id' => $this->get('facebook_page_id', null, 'automation_sources'),
            ],
            'instagram' => [
                'enabled' => $this->get('instagram_enabled', false, 'automation_sources'),
                'access_token' => $this->get('instagram_access_token', null, 'automation_sources'),
                'business_account_id' => $this->get('instagram_business_account_id', null, 'automation_sources'),
            ],
            'linkedin' => [
                'enabled' => $this->get('linkedin_enabled', false, 'automation_sources'),
                'client_id' => $this->get('linkedin_client_id', null, 'automation_sources'),
                'client_secret' => $this->get('linkedin_client_secret', null, 'automation_sources'),
            ],
            'google_forms' => [
                'enabled' => $this->get('google_forms_enabled', false, 'automation_sources'),
                'api_key' => $this->get('google_forms_api_key', null, 'automation_sources'),
            ],
            'whatsapp' => [
                'enabled' => $this->get('whatsapp_enabled', false, 'automation_sources'),
                'business_api_token' => $this->get('whatsapp_business_api_token', null, 'automation_sources'),
                'phone_number_id' => $this->get('whatsapp_phone_number_id', null, 'automation_sources'),
            ],
        ];
    }

    /**
     * Get company profile settings
     */
    public function getCompanyProfile(): array
    {
        return [
            'name' => $this->get('company_name', config('app.name'), 'company'),
            'email' => $this->get('company_email', null, 'company'),
            'phone' => $this->get('company_phone', null, 'company'),
            'address' => $this->get('company_address', null, 'company'),
            'city' => $this->get('company_city', null, 'company'),
            'country' => $this->get('company_country', null, 'company'),
            'timezone' => $this->get('company_timezone', 'UTC', 'company'),
            'currency' => $this->get('company_currency', 'USD', 'company'),
            'logo' => $this->get('company_logo', null, 'company'),
            'website' => $this->get('company_website', null, 'company'),
        ];
    }

    /**
     * Get communication settings
     */
    public function getCommunicationSettings(): array
    {
        return [
            'email' => [
                'enabled' => $this->get('email_enabled', true, 'communication'),
                'driver' => $this->get('email_driver', 'smtp', 'communication'),
                'host' => $this->get('email_host', null, 'communication'),
                'port' => $this->get('email_port', 587, 'communication'),
                'username' => $this->get('email_username', null, 'communication'),
                'password' => $this->get('email_password', null, 'communication'),
                'from_address' => $this->get('email_from_address', null, 'communication'),
                'from_name' => $this->get('email_from_name', null, 'communication'),
            ],
            'sms' => [
                'enabled' => $this->get('sms_enabled', false, 'communication'),
                'provider' => $this->get('sms_provider', 'twilio', 'communication'),
                'twilio' => [
                    'account_sid' => $this->get('sms_twilio_account_sid', null, 'communication'),
                    'auth_token' => $this->get('sms_twilio_auth_token', null, 'communication'),
                    'from_number' => $this->get('sms_twilio_from_number', null, 'communication'),
                ],
            ],
            'whatsapp' => [
                'enabled' => $this->get('whatsapp_enabled', false, 'communication'),
                'access_token' => $this->get('whatsapp_access_token', null, 'communication'),
                'phone_number_id' => $this->get('whatsapp_phone_number_id', null, 'communication'),
            ],
        ];
    }

    /**
     * Get payment gateway settings
     */
    public function getPaymentSettings(): array
    {
        return [
            'stripe' => [
                'enabled' => $this->get('stripe_enabled', false, 'payment'),
                'public_key' => $this->get('stripe_public_key', null, 'payment'),
                'secret_key' => $this->get('stripe_secret_key', null, 'payment'),
            ],
            'paypal' => [
                'enabled' => $this->get('paypal_enabled', false, 'payment'),
                'client_id' => $this->get('paypal_client_id', null, 'payment'),
                'secret' => $this->get('paypal_secret', null, 'payment'),
                'mode' => $this->get('paypal_mode', 'sandbox', 'payment'),
            ],
        ];
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate(array $settings): void
    {
        foreach ($settings as $group => $groupSettings) {
            foreach ($groupSettings as $key => $value) {
                $this->set($key, $value, $group);
            }
        }
    }

    /**
     * Clear all settings cache
     */
    public function clearCache(): void
    {
        try {
            Cache::tags(['settings'])->flush();
        } catch (\BadMethodCallException $e) {
            // Fallback when cache store doesn't support tags (e.g., database cache)
            Cache::flush();
        }
    }

    /**
     * Cast value to appropriate type
     */
    protected function castValue($value, string $type)
    {
        return match($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'array', 'json' => is_string($value) ? json_decode($value, true) : $value,
            'string' => (string) $value,
            default => $value,
        };
    }

    /**
     * Detect value type
     */
    protected function detectType($value): string
    {
        if (is_bool($value)) return 'boolean';
        if (is_int($value)) return 'integer';
        if (is_float($value)) return 'float';
        if (is_array($value)) return 'array';
        return 'string';
    }
}

