<?php

namespace App\Models;

use App\Models\CmsSection;
use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SectionType extends Model
{
    use HasMedia;

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
