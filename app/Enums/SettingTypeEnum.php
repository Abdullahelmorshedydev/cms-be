<?php

namespace App\Enums;

enum SettingTypeEnum: int
{
    case IMAGE   = 1;
    case TEXT    = 2;
    case INTEGER = 3;
    case ICON    = 4;

    public static function values(): array
    {
        return [
            self::IMAGE->value,
            self::TEXT->value,
            self::INTEGER->value,
            self::ICON->value,
        ];
    }

    public function inputType(): string
    {
        return match ($this) {
            self::IMAGE   => 'file',
            self::TEXT    => 'text',
            self::INTEGER => 'number',
            self::ICON    => 'text',
        };
    }

    public function lang(): string
    {
        return match ($this) {
            self::IMAGE   => __('custom.enums.image'),
            self::TEXT    => __('custom.enums.text'),
            self::INTEGER => __('custom.enums.integer'),
            self::ICON    => __('custom.enums.icon'),
        };
    }
}
