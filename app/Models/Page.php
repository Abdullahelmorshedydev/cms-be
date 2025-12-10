<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\PageFactory;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    // protected static function newFactory(): PageFactory
    // {
    //     return PageFactory::new();
    // }

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
