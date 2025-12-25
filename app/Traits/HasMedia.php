<?php

namespace App\Traits;

use App\Enums\MediaTypeEnum;
use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasMedia
{/**
 * =========================
 * General relations
 * =========================
 */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    /**
     * =========================
     * Single relations (MorphOne)
     * =========================
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', MediaTypeEnum::IMAGE->value);
    }

    public function video(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', MediaTypeEnum::VIDEO->value);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', MediaTypeEnum::FILE->value);
    }

    public function icon(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', MediaTypeEnum::ICON->value);
    }

    /**
     * =========================
     * Multiple relations (MorphMany)
     * =========================
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediaable')
            ->where('type', MediaTypeEnum::IMAGE->value)
            ->orderBy('order');
    }

    public function videos(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediaable')
            ->where('type', MediaTypeEnum::VIDEO->value)
            ->orderBy('order');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediaable')
            ->where('type', MediaTypeEnum::FILE->value)
            ->orderBy('order');
    }

    public function icons(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediaable')
            ->where('type', MediaTypeEnum::ICON->value)
            ->orderBy('order');
    }
}
