<?php

namespace App\Enums;

use App\Models\Page;

enum SectionModelTypeEnum: string
{
    case pages = Page::class;

    public function title(): string
    {
        return ucfirst(str_replace('_', ' ', $this->name));
    }

    public static function getTablesNames(): array
    {
        return [
            (new Page())->getTable(),
        ];
    }
}
