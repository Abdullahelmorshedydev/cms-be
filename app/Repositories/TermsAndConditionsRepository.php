<?php

namespace App\Repositories;
use App\Repositories\BaseRepository;
use Models\TermsAndConditions;

class TermsAndConditionsRepository extends BaseRepository
{
    protected function model(): string
    {
        return "TermsAndConditions::class";
    }
}
