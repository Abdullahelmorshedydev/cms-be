<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\HasMedia;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasFactory, HasTranslations, HasMedia, HasSlug;

    const IMG_PATH = 'uploads/images/projects';
    const VIDEO_PATH = 'uploads/videos/projects';
    const FILE_PATH = 'uploads/files/projects';
    const ICON_PATH = 'uploads/icons/projects';

    protected $translatable = [
        'name',
        'short_description',
        'description'
    ];

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'url',
        'status',
        'date'
    ];

    protected $casts = [
        'status' => StatusEnum::class,
        'date' => 'date'
    ];
}
