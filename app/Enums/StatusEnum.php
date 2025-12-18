<?php

namespace App\Enums;

enum StatusEnum: int
{
    case ACTIVE   = 1;
    case INACTIVE = 2;

    public static function values(): array
    {
        return [
            self::ACTIVE->value,
            self::INACTIVE->value,
        ];
    }

    public function classTable(): string
    {
        return match ($this) {
            self::ACTIVE   => 'success',
            self::INACTIVE => 'secondary',
        };
    }

    public function lang(): string
    {
        return match ($this) {
            self::ACTIVE   => __('custom.enums.active'),
            self::INACTIVE => __('custom.enums.inactive'),
        };
    }

    public static function getAll(): array
    {
        return [
            [
                'value' => self::ACTIVE->value,
                'lang'  => self::ACTIVE->lang(),
            ],
            [
                'value' => self::INACTIVE->value,
                'lang' => self::INACTIVE->lang(),
            ]
        ];
    }

    public function getLangValue(): array
    {
        return match ($this) {
            self::ACTIVE   => [
                'value'=> self::ACTIVE->value,
                'lang'=> self::ACTIVE->lang(),
            ],
            self::INACTIVE => [
                'value'=> self::INACTIVE->value,
                'lang'=> self::INACTIVE->lang(),
            ],
        };
    }
}
