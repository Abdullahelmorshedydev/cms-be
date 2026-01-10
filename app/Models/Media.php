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
        // 'collection_name' - Removed: column doesn't exist in database table
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
}
