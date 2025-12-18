<?php

namespace App\Enums;

enum SettingGroupEnum: int
{
    case MAIN   = 1;
    case SOCIAL = 2;

    public static function values(): array
    {
        return [
            self::MAIN->value,
            self::SOCIAL->value,
        ];
    }

    public function lang(): string
    {
        return match ($this) {
            self::MAIN   => __('custom.enums.main'),
            self::SOCIAL => __('custom.enums.social'),
        };
    }
}
