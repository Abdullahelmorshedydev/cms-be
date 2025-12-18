<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    /**
     * Boot the trait
     */
    public static function bootCacheable()
    {
        static::created(function ($model) {
            $model->clearModelCache();
        });

        static::updated(function ($model) {
            $model->clearModelCache();
        });

        static::deleted(function ($model) {
            $model->clearModelCache();
        });
    }

    /**
     * Get cache key for this model
     */
    public function getCacheKey(string $suffix = ''): string
    {
        $key = strtolower(class_basename($this)) . ':' . $this->getKey();
        return $suffix ? $key . ':' . $suffix : $key;
    }

    /**
     * Get cache tags for this model
     */
    public function getCacheTags(): array
    {
        return [
            strtolower(class_basename($this)),
            $this->getCacheKey(),
        ];
    }

    /**
     * Check if the current cache store supports tags
     */
    protected function cacheSupportsTags(): bool
    {
        $store = Cache::getStore();
        return method_exists($store, 'tags') && $store->supportsTags();
    }

    /**
     * Remember a value for this model
     */
    public function rememberCache(string $key, $ttl, callable $callback)
    {
        $cacheKey = $this->getCacheKey($key);
        if ($this->cacheSupportsTags()) {
            return Cache::tags($this->getCacheTags())
                ->remember($cacheKey, $ttl, $callback);
        }
        return Cache::remember($cacheKey, $ttl, $callback);
    }

    /**
     * Clear all cache for this model
     */
    public function clearModelCache(): void
    {
        if ($this->cacheSupportsTags()) {
            Cache::tags($this->getCacheTags())->flush();
        } else {
            Cache::flush(); // Fallback: clear all cache if tags not supported
        }
    }

    /**
     * Clear specific cache key for this model
     */
    public function forgetCache(string $key): void
    {
        $cacheKey = $this->getCacheKey($key);
        if ($this->cacheSupportsTags()) {
            Cache::tags($this->getCacheTags())->forget($cacheKey);
        } else {
            Cache::forget($cacheKey);
        }
    }
}


