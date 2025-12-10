<?php

namespace App\Repositories;
use App\Repositories\BaseRepository;
use Models\Faq;

class FaqRepository extends BaseRepository
{
    protected function model(): string
    {
        return "Faq::class";
    }
}
