<?php

namespace App\Traits;

use App\Enums\StatusEnum;

trait ScopeActive
{
    public function scopeActive($query)
    {
        return $query->where('is_active', StatusEnum::ACTIVE->value);
    }
}
