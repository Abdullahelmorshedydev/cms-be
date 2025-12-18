<?php

namespace App\Models;

use App\Enums\MediaTypeEnum;
use App\Enums\StatusEnum;
use App\Traits\ScopeActive;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Blog extends Model
{
    use HasFactory, ScopeActive, HasTranslations, SoftDeletes;

    protected $translatable = [
        'title',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_by',
        'is_active',
        'published_at'
    ];

    protected $casts = [
        'title'            => 'array',
        'content'          => 'array',
        'excerpt'          => 'array',
        'meta_title'       => 'array',
        'meta_description' => 'array',
        'meta_keywords'    => 'array',
        'is_active'        => StatusEnum::class,
        'published_at'     => 'datetime',
    ];

    /**
     * Get the user who created the blog
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all comments for this blog
     */
    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get active comments only
     */
    public function activeComments(): HasMany
    {
        return $this->hasMany(BlogComment::class)
            ->where('is_active', StatusEnum::ACTIVE->value)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all comments including replies
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the featured image for the blog
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', MediaTypeEnum::IMAGE->value);
    }

    /**
     * Get image path accessor
     */
    public function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image ? $this->image->url : asset('dashboard/assets/img/blog-default.png'),
        );
    }

    /**
     * Scope to get published blogs
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    /**
     * Scope to get blogs by creator
     */
    public function scopeByCreator($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}

