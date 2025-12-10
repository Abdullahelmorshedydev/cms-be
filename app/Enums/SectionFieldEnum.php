<?php

namespace App\Enums;

enum SectionFieldEnum: string
{
  case title = 'title';
  case subtitle = 'subtitle';
  case description = 'description';
  case short_description = 'short_description';
  case image = 'image';
  case gallery = 'gallery';
  case video = 'video';
  case icon = 'icon';
  case button = 'button';
  case buttons = 'buttons';
  case model = 'model';

  public static function values(): array
  {
    return array_column(self::cases(), 'value');
  }
}
