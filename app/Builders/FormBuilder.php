<?php

namespace App\Builders;

use App\Enums\FormTypeEnum;
use App\Enums\StatusEnum;

class FormBuilder extends BaseBuilder
{
    /**
     * Build data for index view
     */
    public function index(): array
    {
        return [
            'types' => FormTypeEnum::toArray(),
            'status' => StatusEnum::cases(),
        ];
    }

    /**
     * Build data for show view
     */
    public function show($form): array
    {
        return [
            'form' => $form,
        ];
    }

    /**
     * Build data for form email create/edit views
     */
    public function formEmailIndex(): array
    {
        return [
            'types' => FormTypeEnum::toArray(),
            'status' => StatusEnum::cases(),
        ];
    }
}
