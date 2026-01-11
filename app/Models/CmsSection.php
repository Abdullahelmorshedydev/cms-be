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

    const IMG_PATH = 'uploads/images/sections';
    const VIDEO_PATH = 'uploads/videos/sections';
    const FILE_PATH = 'uploads/files/sections';
    const ICON_PATH = 'uploads/icons/sections';

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

    // REMOVED ALL automatic eager loading to prevent infinite loops and memory exhaustion
    // All relationships should be loaded explicitly when needed using ->with() or ->load()
    protected $with = [];

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
            if ($model->model) {
                // Only load images if the model uses HasMedia trait
                // Page model doesn't have images relationship, so check trait usage first
                $traits = class_uses_recursive(get_class($model->model));
                if (isset($traits[\App\Traits\HasMedia::class])) {
                    try {
                        $model->model->load('images');
                    } catch (\Exception $e) {
                        // If loading images fails, just continue without it
                        // This prevents errors for models that don't have images relationship
                    }
                }
                $models[] = $model->model;
            }
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
            SectionType::class,
            'cms_section_section_type',
            'cms_section_id',
            'section_type_id'
        )
            ->using(CmsSectionSectionType::class)
            ->withTimestamps();
    }
}
