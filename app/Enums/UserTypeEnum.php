<?php

namespace App\Enums;

enum UserTypeEnum: int
{
    case ADMIN = 1;
    case STUDENT = 2;
    case PARENT = 3;

    public static function values(): array
    {
        return [
            self::ADMIN->value,
            self::STUDENT->value,
            self::PARENT->value,
        ];
    }

    public function lang(): string
    {
        return match ($this) {
            self::ADMIN => __('custom.enums.admin'),
            self::STUDENT => __('custom.enums.student'),
            self::PARENT => __('custom.enums.parent'),
        };
    }

    public static function getAll(): array
    {
        return [
            [
                'value' => self::ADMIN->value,
                'lang' => self::ADMIN->lang(),
            ],
            [
                'value' => self::STUDENT->value,
                'lang' => self::STUDENT->lang(),
            ],
            [
                'value' => self::PARENT->value,
                'lang' => self::PARENT->lang(),
            ],
        ];
    }

    public function getLangValue(): array
    {
        return match ($this) {
            self::ADMIN => [
                'value' => self::ADMIN->value,
                'lang' => self::ADMIN->lang(),
            ],
            self::STUDENT => [
                'value' => self::STUDENT->value,
                'lang' => self::STUDENT->lang(),
            ],
            self::PARENT => [
                'value' => self::PARENT->value,
                'lang' => self::PARENT->lang(),
            ],
        };
    }
}

