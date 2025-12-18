<?php

namespace App\Builders;

use App\Builders\BaseBuilder;

class UserBuilder extends BaseBuilder
{
    protected $roles = null;
    protected $genders = null;

    public function setRoles($roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function setGenders($genders): static
    {
        $this->genders = $genders;
        return $this;
    }

    public function create(): array
    {
        return array_merge(parent::create(), [
            'roles' => $this->roles,
            'genders' => $this->genders
        ]);
    }

    public function edit(mixed $model): array
    {
        return array_merge(parent::edit($model), [
            'roles' => $this->roles,
            'genders' => $this->genders
        ]);
    }
}
