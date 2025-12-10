<?php

namespace App\Enums;

enum SectionButtonTypeEnum: string
{
    case call = 'call';
    case url = 'url';

    public function title(): string
    {
        return ucfirst(str_replace('_', ' ', $this->name));
    }
}
