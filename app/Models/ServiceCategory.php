<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ServiceCategory extends Model
{
    use HasFactory, HasTranslations, HasSlug;

    protected $translatable = [
        'name'
    ];

    protected $fillable = [
        'name',
        'slug'
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}