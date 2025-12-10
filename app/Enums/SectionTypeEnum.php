<?php

namespace App\Enums;

enum SectionTypeEnum: string
{
  case text = "text";
  case image = "image";
  case gallery = "gallery";
  case video = 'video';
  case text_with_icon = "text_with_icon";
  case text_with_image = "text_with_image";
  case text_with_gallery = "text_with_gallery";
  case text_with_video = "text_with_video";
  case text_with_image_and_icon = "text_with_image_and_icon";
  case buttons_section = "buttons_section";

  public static function values(): array
  {
    return array_column(self::cases(), 'value');
  }
}
