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
