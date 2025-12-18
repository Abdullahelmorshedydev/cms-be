<?php

namespace App\Services;

use App\Builders\FormBuilder;
use App\Repositories\FormEmailRepository;

class FormEmailService extends BaseService
{
    public function __construct(FormEmailRepository $repository, protected FormBuilder $builder)
    {
        parent::__construct($repository);
    }
    /**
     * Get all form emails
     */
    public function getAllPaginated($data = [], $with = [], $columns = ['*'], $order = ['created_at' => 'DESC'], $limit = 15)
    {
        return $this->repository->findByWith($data, $columns, $with, $order, $limit);
    }

    /**
     * Show single form email
     */
    public function show($key, $value, $with = [])
    {
        return parent::show($key, $value, $with);
    }

    /**
     * Create new form email - Uses parent store method
     */
    public function create(array $data)
    {
        return parent::store($data);
    }

    /**
     * Update form email
     */
    public function edit($id, array $data)
    {
        return parent::update($data, $id, 'id');
    }

    /**
     * Delete form email
     */
    public function remove($id)
    {
        return parent::destroy('id', $id);
    }
}

