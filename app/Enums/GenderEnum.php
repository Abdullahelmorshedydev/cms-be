<?php

namespace App\Enums;

enum GenderEnum: int
{
    case MALE   = 1;
    case FEMALE = 2;

    public static function values(): array
    {
        return [
            self::MALE->value,
            self::FEMALE->value,
        ];
    }

    public function lang(): string
    {
        return match ($this) {
            self::MALE   => __('custom.enums.male'),
            self::FEMALE => __('custom.enums.female'),
        };
    }

    public static function getAll(): array
    {
        return [
            [
                'value' => self::MALE->value,
                'lang'  => self::MALE->lang(),
            ],
            [
                'value' => self::FEMALE->value,
                'lang' => self::FEMALE->lang(),
            ]
        ];
    }

    public function getLangValue(): array
    {
        return match ($this) {
            self::MALE   => [
                'value' => self::MALE->value,
                'lang' => self::MALE->lang(),
            ],
            self::FEMALE => [
                'value' => self::FEMALE->value,
                'lang' => self::FEMALE->lang(),
            ],
        };
    }
}
