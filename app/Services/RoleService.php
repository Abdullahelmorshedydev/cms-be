<?php

namespace App\Services;

use App\Builders\RoleBuilder;
use App\Enums\StatusEnum;
use App\Repositories\RoleRepository;
use App\Repositories\PermissionRepository;
use Symfony\Component\HttpFoundation\Response;

class RoleService extends BaseService
{
    public function __construct(
        RoleRepository $repository,
        protected RoleBuilder $builder,
        protected PermissionRepository $permissionRepository
    ) {
        parent::__construct($repository);
    }

    public function index($data, $with = [], $columns = ['*'], $order = ['id' => 'DESC'], $limit = 10)
    {
        $result = parent::index($data, $with, $columns, $order, $limit);
        $result['data']['status'] = StatusEnum::getAll();
        $result['data']['permissions'] = $this->permissionRepository->findBy(
            [
                'guard_name' => request()->ajax() || request()->expectsJson() ? 'sanctum' : 'web',
                'name' => [
                    'operator' => '!=',
                    'value' => 'telescope'
                ]
            ],
            [
                'id',
                'name',
                'display_name',
                'display_group_name',
                'group'
            ]
        );
        return $result;
    }

    public function create()
    {
        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->setPermissions(
                $this->permissionRepository->groupByWith(
                    [
                        'guard_name' => request()->ajax() || request()->expectsJson() ? 'sanctum' : 'web',
                        'name' => [
                            'operator' => '!=',
                            'value' => 'telescope.show'
                        ]
                    ],
                    'group',
                    [],
                    [
                        'id',
                        'name',
                        'display_name',
                        'group',
                        'display_group_name'
                    ],
                    [
                        'id' => 'ASC'
                    ]
                )
            )->create(),
            __('custom.messages.retrieved_success')
        );
    }

    public function store($data, callable $callback = null)
    {
        return parent::store($data, function ($model, $data) {
            $model->givePermissionTo($data['permissions']);
        });
    }

    public function show($key, $value, $with = [])
    {
        return parent::show($key, $value, $with);
    }

    public function edit($id)
    {
        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->setPermissions(
                $this->permissionRepository->groupByWith(
                    [
                        'guard_name' => request()->ajax() || request()->expectsJson() ? 'sanctum' : 'web',
                        'name' => [
                            'operator' => '!=',
                            'value' => 'telescope.show'
                        ]
                    ],
                    'group',
                    [],
                    [
                        'id',
                        'name',
                        'display_name',
                        'group',
                        'display_group_name'
                    ],
                    [
                        'id' => 'ASC'
                    ]
                )
            )->edit($this->repository->findOneByWith(
                        [
                            'id' => $id,
                            'name' => [
                                'operator' => 'not in',
                                'value' => [
                                    'super-admin',
                                    'api-super-admin'
                                ]
                            ],
                            'guard_name' => request()->ajax() || request()->expectsJson() ? 'sanctum' : 'web'
                        ],
                        [
                            'id',
                            'name',
                            'display_name'
                        ],
                        [
                            'permissions:id,name,display_name,group,display_group_name,guard_name',
                        ]
                    )),
            __('custom.messages.retrieved_success')
        );
    }

    public function update($data, $value, $key = 'id', callable $callback = null)
    {
        return parent::update($data, $value, $key, function ($model, $data) {
            $model->syncPermissions($data['permissions']);
        });
    }

    public function destroy($key, $value, callable $beforeDelete = null)
    {
        return parent::destroy($key, $value, function ($model) {
            $model->permissions()->detach();
            $model->users()->detach();
        });
    }

    public function destroyAll(array $ids, callable $beforeDelete = null)
    {
        return parent::destroyAll($ids, function ($model) {
            $model->permissions()->detach();
            $model->users()->detach();
        });
    }
}
