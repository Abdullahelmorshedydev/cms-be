<?php

namespace App\Services;

use App\Enums\MediaTypeEnum;
use App\Helpers\MediaHandler;

class MediaService
{
    private string $disk;

    public function __construct()
    {
        // Force use of 'public' disk (local storage) instead of default filesystem
        // This ensures files are stored locally in storage/app/public, not AWS S3
        $this->disk = 'public';
    }

    public function uploadImage($image, $model, string $altText)
    {
        return $this->uploadSingle($model, 'image', $image, $altText, MediaTypeEnum::IMAGE->value, $model::IMG_PATH);
    }

    public function uploadImages($images, $model, string $altText, ?array $removeIds = null, bool $removeForce = false)
    {
        return $this->uploadMany($model, 'images', $images, $altText, MediaTypeEnum::IMAGE->value, $model::IMG_PATH, $removeIds, $removeForce);
    }

    public function removeImage($model)
    {
        $this->removeSingle($model, 'image');
    }

    public function removeImages($model, ?array $ids = null, bool $force = false)
    {
        $this->removeMany($model, 'images', $ids, $force);
    }

    // -------------------------
    // Videos
    // -------------------------
    public function uploadVideo($video, $model, string $altText)
    {
        return $this->uploadSingle($model, 'video', $video, $altText, MediaTypeEnum::VIDEO->value, $model::VIDEO_PATH);
    }

    public function uploadVideos($videos, $model, string $altText, ?array $removeIds = null, bool $removeForce = false)
    {
        return $this->uploadMany($model, 'videos', $videos, $altText, MediaTypeEnum::VIDEO->value, $model::VIDEO_PATH, $removeIds, $removeForce);
    }

    public function removeVideo($model)
    {
        $this->removeSingle($model, 'video');
    }

    public function removeVideos($model, ?array $ids = null, bool $force = false)
    {
        $this->removeMany($model, 'videos', $ids, $force);
    }

    // -------------------------
    // Files
    // -------------------------
    public function uploadFile($file, $model, string $altText)
    {
        return $this->uploadSingle($model, 'file', $file, $altText, MediaTypeEnum::FILE->value, $model::FILE_PATH);
    }

    public function uploadFiles($files, $model, string $altText, ?array $removeIds = null, bool $removeForce = false)
    {
        return $this->uploadMany($model, 'files', $files, $altText, MediaTypeEnum::FILE->value, $model::FILE_PATH, $removeIds, $removeForce);
    }

    public function removeFile($model)
    {
        $this->removeSingle($model, 'file');
    }

    public function removeFiles($model, ?array $ids = null, bool $force = false)
    {
        $this->removeMany($model, 'files', $ids, $force);
    }

    // -------------------------
    // Icons
    // -------------------------
    public function uploadIcon($icon, $model, string $altText)
    {
        return $this->uploadSingle($model, 'icon', $icon, $altText, MediaTypeEnum::ICON->value, $model::ICON_PATH);
    }

    public function uploadIcons($icons, $model, string $altText, ?array $removeIds = null, bool $removeForce = false)
    {
        return $this->uploadMany($model, 'icons', $icons, $altText, MediaTypeEnum::ICON->value, $model::ICON_PATH, $removeIds, $removeForce);
    }

    public function removeIcon($model)
    {
        $this->removeSingle($model, 'icon');
    }

    public function removeIcons($model, ?array $ids = null, bool $force = false)
    {
        $this->removeMany($model, 'icons', $ids, $force);
    }

    // =========================
    // Core
    // =========================
    public function uploadSingle($model, string $relationName, $file, string $altText, int|string $type, string $basePath)
    {
        $this->removeSingle($model, $relationName);

        $storedName = MediaHandler::store($file, $this->disk, $basePath);

        $media = $model->{$relationName}()->create([
            'name' => $storedName,
            'media_path' => MediaHandler::normalizePath($basePath),
            'type' => $type,
            'alt_text' => $altText,
            'device' => 'desktop'
        ]);

        return (int) $media->id;
    }

    public function uploadMany($model, string $relationName, iterable $files, string $altText, int|string $type, string $basePath, ?array $removeIds = null, bool $removeForce = false)
    {
        $this->removeMany($model, $relationName, $removeIds, $removeForce);

        $ids = [];
        $index = 0;

        foreach ($files as $file) {
            if (!isset($file['file']) && isset($file['id']) && isset($file['order'])) {
                $model->{$relationName}()
                    ->where('id', $file['id'])
                    ->update([
                        'order' => $file['order'],
                        'device' => $file['device'],
                    ]);

                $ids[] = (int) $file['id'];
                $index++;
                continue;
            }

            if (isset($file['file'])) {
                $storedName = MediaHandler::store($file['file'], $this->disk, $basePath);

                $media = $model->{$relationName}()->create([
                    'name' => $storedName,
                    'media_path' => MediaHandler::normalizePath($basePath),
                    'type' => $type,
                    'alt_text' => trim($altText . ' ' . $index),
                    'device' => $file['device'] ?? 'desktop',
                    'order' => $file['order'] ?? $index,
                    // 'collection_name' => $file['collection_name'] ?? null,
                ]);

                $ids[] = (int) $media->id;
                $index++;
                continue;
            }

            $index++;
        }

        return $ids;
    }

    public function removeSingle($model, string $relationName)
    {
        $media = $model->{$relationName};

        if (!$media)
            return;

        $this->deleteMediaFileIfExists($media);
        $media->delete();
    }

    public function removeMany($model, string $relationName, ?array $ids = null, bool $force = false)
    {
        if (($ids === null || empty($ids)) && $force === false)
            return;

        $query = $model->{$relationName}();

        if ($ids !== null && $force === false)
            $query->whereIn('id', $ids);

        $items = $query->get();

        foreach ($items as $media) {
            $this->deleteMediaFileIfExists($media);
            $media->delete();
        }
    }

    public function deleteMediaFileIfExists(object $media): void
    {
        if (MediaHandler::exists($media, $this->disk))
            MediaHandler::delete($media, $this->disk);
    }
}
