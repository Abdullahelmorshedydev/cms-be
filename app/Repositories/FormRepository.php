<?php

namespace App\Repositories;

use App\Interfaces\FormRepositoryInterface;
use App\Models\Form;

class FormRepository extends BaseRepository implements FormRepositoryInterface
{
    public function model(): string
    {
        return Form::class;
    }
}
