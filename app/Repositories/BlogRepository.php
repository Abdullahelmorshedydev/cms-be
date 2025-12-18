<?php

namespace App\Repositories;

use App\Interfaces\BlogRepositoryInterface;
use App\Models\Blog;

class BlogRepository extends BasicRepository
{
    public function model(): string
    {
        return Blog::class;
    }
}

