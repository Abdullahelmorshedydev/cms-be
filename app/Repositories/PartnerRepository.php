<?php

namespace App\Repositories;

use App\Models\Partner;

class PartnerRepository extends BasicRepository
{
    public function model(): string
    {
        return Partner::class;
    }
}
