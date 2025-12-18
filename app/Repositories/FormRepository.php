<?php

namespace App\Repositories;

use App\Models\Form;

class FormRepository extends BaseRepository
{
    public function model(): string
    {
        return Form::class;
    }
}
