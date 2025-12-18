<?php

namespace App\Models;

use App\Enums\FormTypeEnum;
use App\Enums\StatusEnum;
use App\Traits\ScopeActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FormEmail extends Model
{
    use HasFactory, ScopeActive, HasTranslations;

    protected $translatable = [];

    protected $fillable = [
        'name',
        'email',
        'form_types',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => StatusEnum::class,
        'form_types' => 'array',
    ];

    /**
     * Check if this email should receive a specific form type
     */
    public function receivesFormType(FormTypeEnum $type): bool
    {
        if (empty($this->form_types)) {
            return false;
        }

        return in_array($type->value, $this->form_types);
    }

    /**
     * Get form types as enum instances
     */
    public function getFormTypesEnumsAttribute(): array
    {
        if (empty($this->form_types)) {
            return [];
        }

        return array_map(
            fn($type) => FormTypeEnum::from($type),
            $this->form_types
        );
    }

    /**
     * Scope to filter by form type
     */
    public function scopeReceivingFormType($query, $type)
    {
        $typeValue = $type instanceof FormTypeEnum ? $type->value : $type;

        return $query->whereJsonContains('form_types', $typeValue);
    }
}
