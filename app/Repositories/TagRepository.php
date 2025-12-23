<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository extends BasicRepository
{
    public function model(): string
    {
        return Tag::class;
    }
}
