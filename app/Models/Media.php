<?php

namespace App\Models;

use App\Enums\MediaTypeEnum;
use App\Enums\StatusEnum;
use App\Traits\ScopeActive;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory, ScopeActive;

    protected $table = 'medias';

    protected $fillable = [
        'mediaable_type',
        'mediaable_id',
        'name',
        'media_path',
        'alt_text',
        'type',
        'is_active',
        'device',
        'collection_name',
    ];

    protected $casts = [
        'is_active' => StatusEnum::class,
        'type' => MediaTypeEnum::class,
    ];

    protected $appends = [
        'url',
        'poster'
    ];

    public function mediaable(): MorphTo
    {
        return $this->morphTo();
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => url('storage/' . $this->media_path . '/' . $this->name),
        );
    }

    public function poster(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->type->hasPoster() ? $this->mediaable->images()->where('device', $this->device)->first() : null,
        );
    }

    /**
     * Get media response format for API
     * Returns standardized media data structure
     */
    public function getMediaResponse(): array
    {
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'media_path' => $this->media_path,
            'alt_text' => $this->alt_text ?? null,
            'type' => $this->type instanceof \App\Enums\MediaTypeEnum ? $this->type->value : $this->type,
            'device' => $this->device ?? null,
            'is_active' => $this->is_active instanceof \App\Enums\StatusEnum ? $this->is_active->value : $this->is_active,
        ];

        // Add poster if it exists and is not null
        // Poster is already an Attribute that returns a Media model or null
        $poster = $this->poster;
        if ($poster instanceof Media) {
            // Return basic poster info to prevent infinite recursion
            $response['poster'] = [
                'id' => $poster->id,
                'name' => $poster->name,
                'url' => $poster->url,
                'media_path' => $poster->media_path,
            ];
        } elseif ($poster !== null) {
            $response['poster'] = $poster;
        }

        // Add timestamps
        if ($this->created_at) {
            $response['created_at'] = $this->created_at->toIso8601String();
        }
        if ($this->updated_at) {
            $response['updated_at'] = $this->updated_at->toIso8601String();
        }

        return $response;
    }
}
