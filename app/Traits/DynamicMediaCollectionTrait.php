<?php

namespace App\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait DynamicMediaCollectionTrait
{
    public function images()
    {
        return $this->morphMany(Media::class, 'model');
    }
    public function getMediaResponse()
    {
        if (!$this->relationLoaded('media'))
            $this->load('media');
        return $this->media->map(function ($image) {
            $imageData = [
                'id' => $image->id,
                'url' => $image->getUrl(),
                'collection_name' => $image->collection_name,
            ];
            $order = $image->getCustomProperty('image_order');
            if ($order) {
                $imageData['order'] = $order;
            }
            return $imageData;
        });
    }

    public function getMediaResponseWithOrder()
    {
        if (!$this->relationLoaded('media'))
            $this->load('media');

        $mappedMedia = $this->media->map(function ($image) {
            $imageData = [
                'id' => $image->id,
                'url' => $image->getUrl(),
                'collection_name' => $image->collection_name,
            ];
            $order = $image->getCustomProperty('image_order') ?? $image->getCustomProperty('order');
            if ($order !== null) {
                $imageData['order'] = $order;
            }
            return $imageData;
        });

        // Check if any media has order property
        $hasOrder = $mappedMedia->contains(function ($media) {
            return isset($media['order']);
        });

        // If any media has order, sort by order ascending
        if ($hasOrder) {
            return $mappedMedia->sortBy(function ($media) {
                return $media['order'] ?? 999; // Put items without order at the end
            })->values();
        }

        return $mappedMedia;
    }
}
