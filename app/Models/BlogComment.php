<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\ScopeActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class BlogComment extends Model
{
    use HasFactory, ScopeActive, SoftDeletes, HasTranslations;

    protected $translatable = [];

    protected $fillable = [
        'blog_id',
        'user_id',
        'name',
        'email',
        'comment',
        'parent_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => StatusEnum::class,
    ];

    /**
     * Get the blog this comment belongs to
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * Get the user who created this comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment
     */
    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment
     */
    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get active replies only
     */
    public function activeReplies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')
            ->where('is_active', StatusEnum::ACTIVE->value)
            ->orderBy('created_at', 'asc');
    }

    /**
     * Scope to get only top-level comments (not replies)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get only replies
     */
    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }
}
