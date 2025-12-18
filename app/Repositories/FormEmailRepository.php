<?php

namespace App\Repositories;

use App\Models\FormEmail;

class FormEmailRepository extends BasicRepository
{
    public function model(): string
    {
        return FormEmail::class;
    }
}

