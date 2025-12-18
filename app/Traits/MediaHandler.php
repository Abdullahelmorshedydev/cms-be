<?php

namespace App\Traits;

use App\Enums\MediaTypeEnum;
use App\Helpers\FileHandler;
use App\Models\Media;

trait MediaHandler
{
    // Images
    private function uploadImage($image, $model, $altText)
    {
        $this->removeImage($model);

        $image = $model->image()->create([
            'name'          => FileHandler::store_img($image, 'public', $model::IMG_PATH),
            'media_path'    => $model::IMG_PATH,
            'type'          => MediaTypeEnum::IMAGE->value,
            'alt_text'      => $altText
        ]);

        return $image->id;
    }

    private function uploadImages($images, $model, $altText, $device = 'desktop', $collectionName = null)
    {
        if ($collectionName) {
            $this->removeImagesByCollection($model, $device, $collectionName);
        } else {
            $this->removeImages($model, $device);
        }

        $imagesIds = [];

        foreach ($images as $key => $image) {
            $image = $model->images()->create([
                'name'          => FileHandler::store_img($image, 'public', $model::IMG_PATH),
                'media_path'    => $model::IMG_PATH,
                'type'          => MediaTypeEnum::IMAGE->value,
                'alt_text'      => $altText . ' ' . $key,
                'device'        => $device,
                'collection_name' => $collectionName
            ]);

            $imagesIds[] = $image->id;
        }

        return $imagesIds;
    }

    private function removeImage($model)
    {
        if ($model->image) {
            if (file_exists($model->image->media_path . $model->image->name)) {
                FileHandler::delete_img($model->image, 'public');
            }
            $model->image->delete();
        }
    }

    private function removeImages($model, $device = null)
    {
        $images = $device ? $model->images()->where('device', $device)->get() : $model->images;
        foreach ($images as $image) {
            if (file_exists($image->media_path . $image->name)) {
                FileHandler::delete_img($image, 'public');
            }
            $image->delete();
        }
    }

    private function removeImagesByCollection($model, $device = null, $collectionName = null)
    {
        $query = $model->images();

        if ($device) {
            $query->where('device', $device);
        }

        if ($collectionName) {
            $query->where('collection_name', $collectionName);
        }

        $images = $query->get();

        foreach ($images as $image) {
            if (file_exists($image->media_path . $image->name)) {
                FileHandler::delete_img($image, 'public');
            }
            $image->delete();
        }
    }

    // Videos
    private function uploadVideo($video, $model, $altText)
    {
        $this->removeVideo($model);

        $video = $model->video()->create([
            'name'          => FileHandler::store_img($video, 'public', $model::VIDEO_PATH),
            'media_path'    => $model::VIDEO_PATH,
            'type'          => MediaTypeEnum::VIDEO->value,
            'alt_text'      => $altText
        ]);
    }

    private function uploadVideos($videos, $model, $altText, $device = 'desktop', $collectionName = null)
    {
        if ($collectionName) {
            $this->removeVideosByCollection($model, $device, $collectionName);
        } else {
            $this->removeVideos($model, $device);
        }

        $videosIds = [];

        foreach ($videos as $key => $video) {
            $video = $model->videos()->create([
                'name'          => FileHandler::store_img($video, 'public', $model::VIDEO_PATH),
                'media_path'    => $model::VIDEO_PATH,
                'type'          => MediaTypeEnum::VIDEO->value,
                'alt_text'      => $altText . ' ' . $key,
                'device'        => $device,
                'collection_name' => $collectionName
            ]);

            $videosIds[] = $video->id;
        }

        return $videosIds;
    }

    private function removeVideo($model)
    {
        if ($model->video) {
            if (file_exists($model->video->video_path . $model->video->name)) {
                FileHandler::delete_img($model->video, 'public');
            }
            $model->video->delete();
        }
    }

    private function removeVideos($model, $device = null)
    {
        $videos = $device ? $model->videos()->where('device', $device)->get() : $model->videos;
        foreach ($videos as $video) {
            if (file_exists($video->media_path . $video->name)) {
                FileHandler::delete_img($video, 'public');
            }
            $video->delete();
        }
    }

    private function removeVideosByCollection($model, $device = null, $collectionName = null)
    {
        $query = $model->videos();

        if ($device) {
            $query->where('device', $device);
        }

        if ($collectionName) {
            $query->where('collection_name', $collectionName);
        }

        $videos = $query->get();

        foreach ($videos as $video) {
            if (file_exists($video->media_path . $video->name)) {
                FileHandler::delete_img($video, 'public');
            }
            $video->delete();
        }
    }

    // Files
    private function uploadFile($file, $model, $altText)
    {
        $this->removeFile($model);

        $file = $model->file()->create([
            'name'          => FileHandler::store_img($file, 'public', $model::FILES_PATH),
            'media_path'    => $model::FILES_PATH,
            'type'          => MediaTypeEnum::FILE->value,
            'alt_text'      => $altText,
        ]);

        return $file->id;
    }

    private function removeFile($model)
    {
        if ($model->file) {
            if (file_exists($model->file->media_path . $model->file->name)) {
                FileHandler::delete_img($model->file, 'public');
            }
            $model->file->delete();
        }
    }

    // Files
    private function uploadIcon($icon, $model, $altText)
    {
        $this->removeIcon($model);

        $icon = $model->icon()->create([
            'name'          => FileHandler::store_img($icon, 'public', $model::ICON_PATH),
            'media_path'    => $model::ICON_PATH,
            'type'          => MediaTypeEnum::ICON->value,
            'alt_text'      => $altText,
        ]);

        return $icon->id;
    }

    private function removeIcon($model)
    {
        if ($model->icon) {
            if (file_exists($model->icon->media_path . $model->icon->name)) {
                FileHandler::delete_img($model->icon, 'public');
            }
            $model->icon->delete();
        }
    }

    // Global
    private function removeMedia($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        if (file_exists($media->media_path . $media->name)) {
            FileHandler::delete_img($media, 'public');
        }
        $media->delete();
    }
}
