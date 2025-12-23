<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository extends BasicRepository
{
    public function model(): string
    {
        return Service::class;
    }
}
