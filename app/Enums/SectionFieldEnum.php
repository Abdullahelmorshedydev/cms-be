<?php

namespace App\Enums;

enum SectionFieldEnum: string
{
    case TITLE = 'title';
    case SUBTITLE = 'subtitle';
    case DESCRIPTION = 'description';
    case SHORT_DESCRIPTION = 'short_description';
    case IMAGE = 'image';
    case GALLERY = 'gallery';
    case VIDEO = 'video';
    case ICON = 'icon';
    case BUTTON = 'button';
    case BUTTONS = 'buttons';
    case MODEL = 'model';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
