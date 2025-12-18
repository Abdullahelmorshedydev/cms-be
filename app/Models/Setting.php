<?php

namespace App\Models;

use App\Enums\MediaTypeEnum;
use App\Enums\SettingGroupEnum;
use App\Enums\SettingTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Setting extends Model
{
    use HasFactory;

    const IMG_PATH = 'uploads/images/settings/';

    protected $fillable = [
        'label',
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    protected $casts = [
        'type'  => SettingTypeEnum::class,
        'group' => SettingGroupEnum::class,
        'label' => 'array',
        'value' => 'array',
    ];

    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', MediaTypeEnum::IMAGE->value);
    }

    public function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn() => $this?->image?->url,
        );
    }
}
