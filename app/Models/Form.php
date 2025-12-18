<?php

namespace App\Models;

use App\Enums\FormTypeEnum;
use App\Enums\StatusEnum;
use App\Traits\ScopeActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Form extends Model
{
    use HasFactory, ScopeActive, HasTranslations;

    protected $translatable = [];

    protected $fillable = [
        'type',
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'position',
        'website',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'subject',
        'message',
        'preferred_date',
        'preferred_time',
        'service_type',
        'additional_notes',
        'referral_source',
        'resume_url',
        'portfolio_url',
        'linkedin_url',
        'years_experience',
        'duration',
        'participants',
        'budget',
        'currency',
        'project_deadline',
        'data',
        'ip_address',
        'user_agent',
        'is_read',
        'is_active',
        'read_at',
    ];

    protected $casts = [
        'type'      => FormTypeEnum::class,
        'is_active' => StatusEnum::class,
        'data'      => 'array',
        'is_read'   => 'boolean',
        'read_at'   => 'datetime',
    ];

    /**
     * Get all emails that should receive this form type
     */
    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(
            FormEmail::class,
            'form_email_types',
            'form_type',
            'form_email_id'
        )->withTimestamps();
    }

    /**
     * Scope to filter by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by read status
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope to filter by unread status
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark form as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark form as unread
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Get form icon
     */
    public function getIconAttribute(): string
    {
        return $this->type->icon();
    }

    /**
     * Get form color
     */
    public function getColorAttribute(): string
    {
        return $this->type->color();
    }

    /**
     * Get form label
     */
    public function getLabelAttribute(): string
    {
        return $this->type->lang();
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return trim($this->first_name . ' ' . $this->last_name);
        }

        return $this->name ?? '';
    }

    /**
     * Get display name (full name or name or email)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: ($this->email ?? __('custom.words.anonymous'));
    }

    /**
     * Get address summary
     */
    public function getAddressSummaryAttribute(): ?string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code,
            $this->country,
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
    }
}
