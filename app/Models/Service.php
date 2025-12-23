<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\HasMedia;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, HasTranslations, HasMedia, HasSlug;

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
        'status'
    ];

    protected $casts = [
        'status' => StatusEnum::class
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
