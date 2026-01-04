<?php

namespace App\Enums;

use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\Tag;

enum SectionModelTypeEnum: string
{
    case pages = Page::class;
    case services = Service::class;
    case projects = Project::class;
    case tags = Tag::class;

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
