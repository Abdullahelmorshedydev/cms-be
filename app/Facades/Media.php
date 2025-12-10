<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * @method static void attachMediaFromRequest(\Illuminate\Database\Eloquent\Model $model, ?array $mediaMap = null, ?\App\Enums\MediaMimeTypeEnum $mimeType = \App\Enums\MediaMimeTypeEnum::IMAGE, ?string $sizeLimit = 900)
 */
class Media extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'media';
    }
}
