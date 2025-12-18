<?php

use App\Enums\SettingTypeEnum;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

if (!function_exists('isActiveRoute')) {
    function isActiveRoute($route, $class = 'active')
    {
        if (is_array($route)) {
            // Check if any route in array matches current route
            foreach ($route as $r) {
                if (Route::is($r)) {
                    return $class;
                }
            }
        } else {
            // Check if single route matches current route
            if (Route::is($route)) {
                return $class;
            }
        }

        return '';
    }
}

if (!function_exists('setting')) {
    function setting($key)
    {
        // Clear cache to ensure fresh data (cache is managed by SettingObserver)
        Cache::forget('settings');

        // Retrieve all settings from cache or database
        $settings = Cache::rememberForever('settings', function () {
            return Setting::get();
        });

        // Find setting by key
        $settings = $settings->where('key', $key)->first();

        // Return image path for image settings, otherwise return value
        return $settings?->type == SettingTypeEnum::IMAGE ? $settings?->image_path : $settings?->value;
    }
}

if (!function_exists('generateMapLink')) {
    function generateMapLink($latitude, $longitude, $provider = 'google')
    {
        if ($provider === 'google') {
            return "https://www.google.com/maps/search/?api=1&query={$latitude},{$longitude}";
        } elseif ($provider === 'apple') {
            return "https://maps.apple.com/?q={$latitude},{$longitude}";
        } else {
            return null;
        }
    }
}

if (!function_exists('getTranslatedValue')) {
    /**
     * Get translated value from translatable field
     * Handles both Spatie\Translatable and manual array translations
     */
    function getTranslatedValue($value, $locale = null)
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            $locale = $locale ?? app()->getLocale();
            return $value[$locale] ?? $value['en'] ?? $value['ar'] ?? '';
        }

        // If it's an object, try to get string representation
        if (is_object($value)) {
            // If object has __toString, use it
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }
            // Otherwise return empty string for objects
            return '';
        }

        // For other types (null, numeric, etc.), convert to string
        return (string) ($value ?? '');
    }
}

if (!function_exists('apiResponse')) {
    function apiResponse($code = 200, $errors = [], $data = [], $message = '', $meta = [])
    {
        // Ensure message is translated if it's empty
        if (empty($message) && $code >= 400) {
            $message = match ($code) {
                400 => __('custom.exceptions.bad_request'),
                401 => __('custom.exceptions.unauthorized'),
                403 => __('custom.exceptions.forbidden'),
                404 => __('custom.exceptions.not_found'),
                405 => __('custom.exceptions.method_not_allowed'),
                422 => __('custom.exceptions.validation_error'),
                429 => __('custom.exceptions.too_many_requests'),
                500 => __('custom.exceptions.server_error'),
                503 => __('custom.exceptions.service_unavailable'),
                default => __('custom.exceptions.unknown_error'),
            };
        }

        return response()->json([
            'success' => $code < 400,
            'code' => $code,
            'errors' => $errors,
            'data' => $data,
            'message' => $message,
            'meta' => $meta
        ], $code);
    }
}

if (!function_exists('formatResponse')) {
    function formatResponse($controller, array $response)
    {
        return $controller->setData($response['data'] ?? [])
            ->setMeta($response['meta'] ?? [])
            ->setCode($response['code'] ?? 200)
            ->setMessage($response['message'] ?? '')
            ->setErrors($response['errors'] ?? [])
            ->customResponse();
    }
}

if (!function_exists('returnData')) {
    function returnData(array $errors = [], int $code = 200, $data = [], string $message = '', array $meta = []): array
    {
        return [
            'errors' => $errors,
            'code' => $code,
            'data' => $data,
            'message' => $message,
            'meta' => $meta
        ];
    }
}

if (!function_exists('handleException')) {
    function handleException(\Exception $e)
    {
        logger($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ]);

        // Use translated error message
        $message = app()->environment('production')
            ? __('custom.exceptions.internal_server_error')
            : $e->getMessage();

        // Ensure code is an integer (getCode() can return string for some exceptions like PDO)
        $exceptionCode = $e->getCode();
        $code = (is_numeric($exceptionCode) && $exceptionCode > 0) ? (int) $exceptionCode : 500;

        // Ensure valid HTTP status code range (100-599)
        if ($code < 100 || $code >= 600) {
            $code = 500;
        }

        return returnData(
            [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $exceptionCode, // Keep original in errors array
                'message' => $e->getMessage(),
            ],
            $code, // Use integer code for returnData
            [],
            $message
        );
    }
}
