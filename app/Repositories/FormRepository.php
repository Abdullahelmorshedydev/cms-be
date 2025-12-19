<?php

namespace App\Repositories;

use App\Models\Form;

class FormRepository extends BasicRepository
{
    public function model(): string
    {
        return Form::class;
    }
}
