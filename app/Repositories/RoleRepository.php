<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepository extends BasicRepository
{
    public function model(): string
    {
        return Role::class;
    }
}
