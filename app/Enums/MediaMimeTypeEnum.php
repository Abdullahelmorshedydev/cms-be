<?php

namespace App\Enums;

enum MediaMimeTypeEnum: string
{
    case FILE = 'file';
    case IMAGE = 'image';
    case VIDEO = 'video';

    public static function getMediaValidationRules(self|null $value): string
    {
        return match ($value) {
            self::IMAGE => 'jpeg,png,jpg,gif,svg,webp',
            self::VIDEO => 'mp4',
            self::FILE => 'pdf,docx,doc,pptx,ppt,txt,xls,xlsx',
            default => 'jpeg,png,jpg,gif,svg,webp,pdf,docx,doc,pptx,ppt,txt,xls,xlsx,mp4',
        };
    }
}
