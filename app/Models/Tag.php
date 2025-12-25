<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\HasMedia;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasFactory, HasTranslations, HasMedia, HasSlug;

    const IMG_PATH = 'uploads/images/tags';
    const VIDEO_PATH = 'uploads/videos/tags';
    const FILE_PATH = 'uploads/files/tags';
    const ICON_PATH = 'uploads/icons/tags';

    protected $translatable = [
        'name'
    ];

    protected $fillable = [
        'name',
        'slug',
        'status'
    ];

    protected $casts = [
        'status' => StatusEnum::class
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
