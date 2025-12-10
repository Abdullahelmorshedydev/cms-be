<?php

namespace App\Repositories;
use App\Models\Page;

class PageRepository extends BaseRepository
{
    protected function model(): string
    {
        return Page::class;
    }
}
