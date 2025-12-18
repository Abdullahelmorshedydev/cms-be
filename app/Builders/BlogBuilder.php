<?php

namespace App\Builders;

use App\Builders\BaseBuilder;
use App\Repositories\UserRepository;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BlogBuilder extends BaseBuilder
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    public function create(): array
    {
        return array_merge(parent::create(), [
            'locales' => LaravelLocalization::getSupportedLanguagesKeys(),
            'users' => $this->userRepository->all(['id', 'name', 'email'])
        ]);
    }

    public function edit(mixed $model): array
    {
        return array_merge(parent::edit($model), [
            'locales' => LaravelLocalization::getSupportedLanguagesKeys(),
            'users' => $this->userRepository->all(['id', 'name', 'email'])
        ]);
    }
}

