<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::creating(function ($model) {
            $model->generateSlug();
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') || $model->isDirty('title')) {
                $model->generateSlug();
            }
        });
    }

    public function generateSlug()
    {
        if (empty($this->slug)) {
            $slugSource = $this->getSlugSource();
            if ($slugSource) {
                $slug = Str::slug($slugSource);
                $originalSlug = $slug;

                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $i++;
                }

                $this->slug = $slug;
            }
        }
    }

    protected function getSlugSource()
    {
        if (isset($this->attributes['name']) && $this->isJson($this->attributes['name'])) {
            return json_decode($this->attributes['name'], true)['en'] ?? null;
        } elseif (isset($this->attributes['title']) && $this->isJson($this->attributes['title'])) {
            return json_decode($this->attributes['title'], true)['en'] ?? null;
        } elseif (isset($this->attributes['name'])) {
            return $this->attributes['name'];
        } elseif (isset($this->attributes['title'])) {
            return $this->attributes['title'];
        }

        return null;
    }

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }
}
