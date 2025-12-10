<?php
namespace App\Helpers;

use App\Actions\Media\AttachMediaCommand;

use App\Enums\MediaMimeTypeEnum;
use Spatie\Image\Image;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Log;

class MediaHelper
{
    /**
     * Attach media from the request to a model.
     *
     * @param Spatie\MediaLibrary\HasMedia $model
     * @param array<string, string> $mediaMap
     * @return void
     */
    public function attachMediaFromRequest(
        HasMedia $model,
        array $mediaMap,
        MediaMimeTypeEnum|null $mimeType = null,
        int $sizeLimit = 9000
    ): void {
        (new AttachMediaCommand($model, $mediaMap, $mimeType, $sizeLimit))->execute();
    }

    public static function processVehicleImageForEmail($fullPath)
    {
        try {
            // Create processed version path
            $pathInfo = pathinfo($fullPath);
            $processedRelativePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_email.jpg';
            $processedFullPath = storage_path('app/public/' . $processedRelativePath);

            // Check if processed version already exists
            if (file_exists($processedFullPath)) {
                return asset('storage/' . $processedRelativePath);
            }

            // Process the image
            Image::load($fullPath)
                ->background('#ffffff') // Set white background
                ->format('jpg')
                ->quality(90)
                ->save($processedFullPath);

            return asset('storage/' . $processedRelativePath);
        } catch (\Exception $e) {
            Log::error('Email image processing failed: ' . $e->getMessage());
        }
    }
}
