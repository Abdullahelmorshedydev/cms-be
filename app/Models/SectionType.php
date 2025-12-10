<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Cms\Models\CmsSection;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SectionType extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'section_types';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'fields',
    ];

    protected $casts = [
        'fields' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($sectionType) {
            if (empty($sectionType->slug)) {
                $sectionType->slug = Str::slug($sectionType->name);
            }
        });
    }

    /**
     * The CMS sections that belong to the section type.
     */
    public function cmsSections()
    {
        return $this->belongsToMany(
            CmsSection::class,
            'cms_section_section_type',
            'section_type_id',
            'cms_section_id'
        )
            ->using(CmsSectionSectionType::class)
            ->withTimestamps();
    }
}
