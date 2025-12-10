<?php

namespace App\Enums;

enum SectionParentTypeEnum: string
{
    case pages = 'pages';
    case sections = 'sections';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    /**
     * Get the fully qualified class name dynamically based on the case.
     */
    public function getClassName(): string
    {
        return match ($this) {
            self::pages => self::buildClassName('Page'),
            self::sections => self::buildClassName('CmsSection'),
        };
    }

    /**
     * Helper function to generate the class name dynamically.
     */
    private static function buildClassName(string $class): string
    {
        return "App\\Models\\$class";
    }

    /**
     * Get enum case from a key.
     */
    public static function fromKey(string $key): string
    {
        return match ($key) {
            'pages' => "App\\Models\\Page",
            'sections' => "App\\Models\\CmsSection",
            default => throw new \InvalidArgumentException("Invalid key: $key"),
        };
    }

    /**
     * Get enum case from a fully qualified class name.
     */
    public static function fromValue(?string $value): self
    {
        return match ($value) {
            null => self::sections,
            self::buildClassName('Page') => self::pages,
            default => throw new \InvalidArgumentException("Invalid value: $value"),
        };
    }
}
