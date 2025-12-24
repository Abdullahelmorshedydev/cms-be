<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\HasSlug;
use App\Traits\RouteKeyName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory, HasTranslations, HasSlug, RouteKeyName;

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

    public function sections()
    {
        return $this->morphMany(CmsSection::class, 'parent');
    }
}
