<?php

namespace App\Models;

use App\Models\CmsSectionSectionType;
use App\Traits\DynamicMediaCollectionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CmsSection extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia, DynamicMediaCollectionTrait;

    protected $table = 'cms_sections';
    protected $fillable = [
        'name',
        'content',
        'parent_id',
        'parent_type',
        'section_id',
        'order',
        'disabled',
        'button_data',
        'button_text',
        'button_type'
    ];
    protected $casts = [
        'content' => 'json',
        'disabled' => 'boolean',
    ];

    public array $translatable = ['button_text'];

    public function sections()
    {
        return $this->hasMany(CmsSection::class, 'section_id', 'id')->orderBy('order', 'ASC');
    }

    public function getParentIdentifier()
    {
        return 'parent_id';
    }
    public function models()
    {
        return $this->hasMany(SectionModel::class, 'section_id');
    }
    public function getModels()
    {
        $models = [];
        $this->models->sortBy('order')->each(function ($model) use (&$models) {
            $model->model?->load('images');
            if ($model->model)
                $models[] = $model->model;
        })->values();
        return $models;
    }

    /**
     * The section types that belong to the CMS section.
     */
    public function sectionTypes()
    {
        return $this->belongsToMany(
            \App\Models\SectionType::class,
            'cms_section_section_type',
            'cms_section_id',
            'section_type_id'
        )
            ->using(CmsSectionSectionType::class)
            ->withTimestamps();
    }
}
