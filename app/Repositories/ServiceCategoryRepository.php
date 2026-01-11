<?php

namespace App\Repositories;

use App\Models\ServiceCategory;

class ServiceCategoryRepository extends BasicRepository
{
    public function model(): string
    {
        return ServiceCategory::class;
    }
}
