<?php

namespace App\Services;

use App\Builders\ServiceCategoryBuilder;
use App\Repositories\ServiceCategoryRepository;
use Symfony\Component\HttpFoundation\Response;

class ServiceCategoryService extends BaseService
{
    public function __construct(
        ServiceCategoryRepository $repository,
        protected ServiceCategoryBuilder $builder
    ) {
        parent::__construct($repository);
    }

    public function index($data, $with = [], $columns = ['*'], $order = ['id' => 'DESC'], $limit = 10)
    {
        // Build query with services count
        $query = $this->repository->query()->withCount('services');
        
        // Eager load relationships if provided
        if (!empty($with)) {
            $query->with($with);
        }
        
        // Apply filters using repository's handleCriteria method
        $builder = $this->repository->handleCriteria($data, $query);
        
        // Apply ordering
        $sortBy = $data['sort_by'] ?? key($order);
        $sortOrder = $data['sort_order'] ?? $order[key($order)];
        $builder->orderBy($sortBy, $sortOrder);
        
        // Paginate
        $paginated = $builder->paginate($data['limit'] ?? $limit);
        
        return returnData([], Response::HTTP_OK, [
            'data' => $paginated->items(),
        ], __('custom.messages.retrieved_success'), [
            'current_page' => $paginated->currentPage(),
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'last_page' => $paginated->lastPage(),
            'next_page_url' => $paginated->nextPageUrl(),
            'prev_page_url' => $paginated->previousPageUrl(),
        ]);
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
        return parent::store($data, $callback);
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
                []
            )),
            __('custom.messages.retrieved_success')
        );
    }

    public function update($data, $value, $key = 'slug', callable $callback = null)
    {
        return parent::update($data, $value, $key, $callback);
    }

    public function destroy($key, $value, callable $beforeDelete = null)
    {
        return parent::destroy($key, $value, $beforeDelete);
    }

    public function destroyAll(array $ids, callable $beforeDelete = null)
    {
        return parent::destroyAll($ids, $beforeDelete);
    }
}
