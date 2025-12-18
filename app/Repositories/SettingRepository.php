<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository extends BasicRepository
{
    public function model(): string
    {
        return Setting::class;
    }
}
