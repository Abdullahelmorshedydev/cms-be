<?php

namespace App\Repositories;

use App\Models\BlogComment;

class BlogCommentRepository extends BasicRepository
{
    public function model(): string
    {
        return BlogComment::class;
    }
}

