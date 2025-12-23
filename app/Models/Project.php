<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasFactory, HasTranslations, HasMedia;

    protected $translatable = [
        'name'
    ];

    protected $fillable = [
        'name',
        'slug',
        'url',
        'status'
    ];

    protected $casts = [
        'status' => StatusEnum::class
    ];
}
