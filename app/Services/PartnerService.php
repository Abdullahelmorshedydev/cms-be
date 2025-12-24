<?php

namespace App\Services;

use App\Builders\PartnerBuilder;
use App\Enums\StatusEnum;
use App\Repositories\PartnerRepository;
use App\Services\MediaService;
use Symfony\Component\HttpFoundation\Response;

class PartnerService extends BaseService
{
    public function __construct(
        PartnerRepository $repository,
        protected PartnerBuilder $builder,
        protected MediaService $mediaService
    ) {
        parent::__construct($repository);
    }

    public function index($data, $with = [], $columns = ['*'], $order = ['id' => 'DESC'], $limit = 10)
    {
        $result = parent::index($data, $with, $columns, $order, $limit);
        return $result;
    }

    public function create()
    {
        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->create(),
            __('custom.messages.retrieved_success')
        );
    }

    public function store($data, callable $callback = null)
    {
        return parent::store($data, function ($model, $data) {
            if (isset($data['image']) && is_file($data['image']))
                $this->mediaService->uploadImage($data['image'], $model, $model->name);
        });
    }

    public function show($key, $value, $with = [])
    {
        return parent::show($key, $value, $with);
    }

    public function edit($slug)
    {
        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->edit($this->repository->findOneByWith(
                ['slug' => $slug],
                ['*'],
                [
                    'image'
                ]
            )),
            __('custom.messages.retrieved_success')
        );
    }

    public function update($data, $value, $key = 'slug', callable $callback = null)
    {
        return parent::update($data, $value, $key, function ($model, $data) {
            if (isset($data['image']) && is_file($data['image']))
                $this->mediaService->uploadImage($data['image'], $model, $model->name);
        });
    }

    public function destroy($key, $value, callable $beforeDelete = null)
    {
        return parent::destroy($key, $value, function ($model) {
            $this->mediaService->removeImage($model);
        });
    }

    public function destroyAll(array $ids, callable $beforeDelete = null)
    {
        return parent::destroyAll($ids, function ($model) {
            $this->mediaService->removeImage($model);
        });
    }
}
