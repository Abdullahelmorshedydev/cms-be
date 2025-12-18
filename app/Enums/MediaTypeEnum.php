<?php

namespace App\Enums;

enum MediaTypeEnum: int
{
    case IMAGE   = 1;
    case VIDEO   = 2;
    case FILE    = 3;
    case GALLERY = 4;
    case ICON    = 5;

    public static function values(): array
    {
        return [
            self::IMAGE->value,
            self::VIDEO->value,
            self::FILE->value,
            self::GALLERY->value,
            self::ICON->value,
        ];
    }

    public function lang(): string
    {
        return match ($this) {
            self::IMAGE   => __('custom.enums.image'),
            self::VIDEO   => __('custom.enums.video'),
            self::FILE    => __('custom.enums.file'),
            self::GALLERY => __('custom.enums.gallery'),
            self::ICON    => __('custom.enums.icon'),
        };
    }
}
