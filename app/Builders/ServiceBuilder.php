<?php

namespace App\Builders;

use App\Builders\BaseBuilder;

class ServiceBuilder extends BaseBuilder
{
    protected $tags = null;

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function create(): array
    {
        return array_merge(parent::create(), [
            'tags' => $this->tags
        ]);
    }

    public function edit(mixed $model): array
    {
        return array_merge(parent::edit($model), [
            'tags' => $this->tags
        ]);
    }
}
