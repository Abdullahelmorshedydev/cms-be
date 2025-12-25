<?php

namespace App\Models;

use App\Enums\SettingGroupEnum;
use App\Enums\SettingTypeEnum;
use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasFactory, HasTranslations, HasMedia;

    const IMG_PATH = 'uploads/images/settings';
    const VIDEO_PATH = 'uploads/videos/settings';
    const FILE_PATH = 'uploads/files/settings';
    const ICON_PATH = 'uploads/icons/settings';

    protected $translatable = [
        'label',
        'value'
    ];

    protected $fillable = [
        'label',
        'key',
        'value',
        'type',
        'group'
    ];

    protected $casts = [
        'type' => SettingTypeEnum::class,
        'group' => SettingGroupEnum::class,
        'label' => 'array',
        'value' => 'array',
    ];

    public function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn() => $this?->image?->url,
        );
    }
}
