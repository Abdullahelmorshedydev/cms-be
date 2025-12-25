<?php

namespace App\Models;

use App\Traits\HasMedia;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Partner extends Model
{
    use HasFactory, HasTranslations, HasMedia, HasSlug;

    const IMG_PATH = 'uploads/images/partners';
    const VIDEO_PATH = 'uploads/videos/partners';
    const FILE_PATH = 'uploads/files/partners';
    const ICON_PATH = 'uploads/icons/partners';

    protected $translatable = [
        'name'
    ];

    protected $fillable = [
        'name',
        'slug'
    ];
}
