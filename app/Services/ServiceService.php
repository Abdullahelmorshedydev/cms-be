<?php

namespace App\Services;

use App\Builders\ServiceBuilder;
use App\Enums\StatusEnum;
use App\Repositories\ServiceCategoryRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\TagRepository;
use App\Services\MediaService;
use Symfony\Component\HttpFoundation\Response;

class ServiceService extends BaseService
{
    public function __construct(
        ServiceRepository $repository,
        protected ServiceBuilder $builder,
        protected MediaService $mediaService,
        protected TagRepository $tagRepository,
        protected ServiceCategoryRepository $serviceCategoryRepository
    ) {
        parent::__construct($repository);
    }

    public function index($data, $with = [], $columns = ['*'], $order = ['id' => 'DESC'], $limit = 10)
    {
        $result = parent::index($data, ['category'], $columns, $order, $limit);
        $result['data']['status'] = StatusEnum::getAll();
        $result['data']['tags'] = $this->tagRepository->findAllWith(
            ['*'],
            [],
            ['id' => 'DESC'],
            null
        );
        return $result;
    }

    public function create()
    {
        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->setTags(
                $this->tagRepository->findAllWith(
                    ['*'],
                    [],
                    ['id' => 'DESC'],
                    null
                )
            )->setCategories(
                $this->serviceCategoryRepository->findAllWith(
                    ['*'],
                    [],
                    ['id' => 'DESC'],
                    null
                )
            )->create(),
            __('custom.messages.retrieved_success')
        );
    }

    public function store($data, callable $callback = null)
    {
        return parent::store($data, function ($model, $data) {
            if (isset($data['image']) && is_file($data['image']))
                $this->mediaService->uploadImage($data['image'], $model, $model->name);
            if (isset($data['tags']) && $data['tags'] && $data['tags'][0])
                $model->tags()->attach($data['tags']);
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
            $this->builder->setTags(
                $this->tagRepository->findAllWith(
                    ['*'],
                    [],
                    ['id' => 'DESC'],
                    null
                )
            )->setCategories(
                $this->serviceCategoryRepository->findAllWith(
                    ['*'],
                    [],
                    ['id' => 'DESC'],
                    null
                )
            )->edit($this->repository->findOneByWith(
                        ['slug' => $slug],
                        ['*'],
                        [
                            'image',
                            'tags',
                            'category'
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
            if (isset($data['tags']) && $data['tags'] && $data['tags'][0])
                $model->tags()->sync($data['tags']);
            else
                $model->tags()->detach();
        });
    }

    public function destroy($key, $value, callable $beforeDelete = null)
    {
        return parent::destroy($key, $value, function ($model) {
            $this->mediaService->removeImage($model);
            $model->tags()->detach();
        });
    }

    public function destroyAll(array $ids, callable $beforeDelete = null)
    {
        return parent::destroyAll($ids, function ($model) {
            $this->mediaService->removeImage($model);
            $model->tags()->detach();
        });
    }
}
