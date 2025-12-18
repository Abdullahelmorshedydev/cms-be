<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait TranslateTrait
{
    public static function translate(string $request_en, string $request_ar, bool $isSlug = false)
    {
        if ($isSlug) {
            return Str::slug(strtolower($request_en));
        }

        return [
            'en' => $request_en,
            'ar' => $request_ar,
        ];
    }
}
