<?php

namespace App\Builders;

use App\Builders\BaseBuilder;

class RoleBuilder extends BaseBuilder
{
    protected $permissions = null;

    public function setPermissions($permissions): static
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function create(): array
    {
        return array_merge(parent::create(), [
            'permissions' => $this->permissions,
        ]);
    }

    public function edit(mixed $model): array
    {
        return array_merge(parent::edit($model), [
            'permissions' => $this->permissions,
        ]);
    }
}
