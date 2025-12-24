<?php

namespace App\Models;

use App\Models\CmsSection;
use App\Traits\HasMedia;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SectionType extends Model
{
    use HasMedia, HasSlug, HasTranslations;

    public $translatable = [
        'name',
        'description'
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'fields'
    ];

    protected $casts = [
        'fields' => 'array'
    ];

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
