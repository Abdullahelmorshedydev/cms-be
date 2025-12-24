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

    protected $translatable = [
        'name'
    ];

    protected $fillable = [
        'name',
        'slug'
    ];
}
