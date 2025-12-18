<?php

namespace App\Builders;

use App\Enums\StatusEnum;

abstract class BaseBuilder
{
    public function create(): array
    {
        return [
            'status' => StatusEnum::getAll(),
        ];
    }

    public function edit(mixed $model): array
    {
        return [
            'status' => StatusEnum::getAll(),
            'record' => $model,
        ];
    }
}
