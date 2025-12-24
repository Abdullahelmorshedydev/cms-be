<?php

namespace App\Models;

use App\Models\CmsSectionSectionType;
use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CmsSection extends Model
{
    use HasFactory, HasTranslations, HasMedia;

    public array $translatable = ['button_text'];

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

    public function getMediaResponse()
    {
        $media = [];
        $this->media->each(function ($mediaItem) use (&$media) {
            $media[] = $mediaItem->getMediaResponse();
        });
        return $media;
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
