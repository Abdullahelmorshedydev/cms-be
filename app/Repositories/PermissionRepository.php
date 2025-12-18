<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;

class PermissionRepository extends BasicRepository
{
    public function model(): string
    {
        return Permission::class;
    }
}
