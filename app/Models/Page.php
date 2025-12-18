<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory, HasTranslations;

    protected $translatable = [];

    protected $fillable = ['name', 'is_active'];

    public function sections()
    {
        return $this->morphMany(CmsSection::class, 'parent');
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($page) {
            $page->slug = Str::slug($page->name);
        });
    }
}
