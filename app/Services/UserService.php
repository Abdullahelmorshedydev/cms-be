<?php

namespace App\Services;

use App\Builders\UserBuilder;
use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\MediaService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class UserService extends BaseService
{
    public function __construct(
        UserRepository $repository,
        protected UserBuilder $builder,
        protected RoleRepository $roleRepository,
        protected MediaService $mediaService
    ) {
        parent::__construct($repository);
    }

    public function index($data, $with = [], $columns = ['*'], $order = ['id' => 'DESC'], $limit = 10)
    {
        $result = parent::index($data, $with, $columns, $order, $limit);
        $result['data']['status'] = StatusEnum::getAll();
        $result['data']['roles'] = $this->roleRepository->findBy(
            [
                'guard_name' => request()->ajax() || request()->expectsJson() ? 'sanctum' : 'web',
                'name' => [
                    'operator' => '!=',
                    'value' => 'super-admin'
                ]
            ],
            [
                'id',
                'name',
                'display_name'
            ]
        );
        return $result;
    }

    public function create()
    {
        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->setGenders(
                GenderEnum::getAll()
            )->setRoles(
                    $this->roleRepository->findBy(
                        [
                            'guard_name' => request()->ajax() || request()->expectsJson() ? 'sanctum' : 'web',
                            'name' => [
                                'operator' => '!=',
                                'value' => 'super-admin'
                            ]
                        ],
                        [
                            'id',
                            'name',
                            'display_name'
                        ]
                    )
                )->create(),
            __('custom.messages.retrieved_success')
        );
    }

    public function store($data, callable $callback = null)
    {
        return parent::store($data, function ($model, $data) {
            if (isset($data['image']) && is_file($data['image'])) {
                $this->mediaService->uploadImage($data['image'], $model, $model->name);
            }
            if (isset($data['role'])) {
                try {
                    // Ensure role exists before assigning
                    $role = Role::firstOrCreate(
                        [
                            'name' => $data['role'],
                            'guard_name' => request()->ajax() || request()->expectsJson() ? 'sanctum' : 'web',
                        ],
                        [
                            'display_name' => [
                                'en' => ucfirst($data['role']),
                                'ar' => $data['role'] === 'student' ? 'طالب' : ($data['role'] === 'parent' ? 'ولي أمر' : ucfirst($data['role']))
                            ],
                        ]
                    );
                    $model->assignRole($role);
                } catch (\Exception $e) {
                    Log::error('Failed to assign role during user creation', [
                        'user_id' => $model->id,
                        'role' => $data['role'],
                        'error' => $e->getMessage()
                    ]);
                }
            }
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
            $this->builder->setGenders(
                GenderEnum::getAll()
            )->setRoles(
                    $this->roleRepository->findBy(
                        [
                            'guard_name' => request()->ajax() || request()->expectsJson() ? 'sanctum' : 'web',
                            'name' => [
                                'operator' => '!=',
                                'value' => 'super-admin'
                            ]
                        ],
                        [
                            'id',
                            'name',
                            'display_name'
                        ]
                    )
                )->edit($this->repository->findOneByWith(
                        ['id' => $id],
                        ['*'],
                        [
                            'image',
                            'roles:id,name,display_name',
                        ]
                    )),
            __('custom.messages.retrieved_success')
        );
    }

    public function update($data, $value, $key = 'id', callable $callback = null)
    {
        return parent::update($data, $value, $key, function ($model, $data) {
            if (isset($data['image']) && is_file($data['image'])) {
                $this->mediaService->uploadImage($data['image'], $model, $model->name);
            }
            if (isset($data['role'])) {
                $model->syncRoles([$data['role']]);
            }
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

    public function login($user, $password, $remember = false)
    {
        $type = filter_var($user, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $adminUser = $this->repository->findOneByWith(
            [
                $type => $user,
            ],
            ['*'],
            [
                'image',
                'roles:id,name,display_name',
                'roles.permissions:id,name,group,display_name,display_group_name'
            ],
            false
        );

        // Check if user exists
        if (!$adminUser) {
            return returnData(
                [],
                Response::HTTP_NOT_FOUND,
                [],
                __('custom.auth.invalid_credentials'),
                []
            );
        }

        if (!Hash::check($password, $adminUser->password)) {
            return returnData(
                [],
                Response::HTTP_NOT_FOUND,
                [],
                __('custom.auth.invalid_credentials'),
                []
            );
        }

        if (!$adminUser->email_verified_at) {
            return returnData(
                [],
                Response::HTTP_NOT_FOUND,
                [],
                __('custom.auth.not_verified'),
                []
            );
        }

        // if ($adminUser->is_active !== StatusEnum::ACTIVE) {
        //     return returnData(
        //         [],
        //         Response::HTTP_NOT_FOUND,
        //         [],
        //         __('custom.auth.not_active'),
        //         []
        //     );
        // }

        $permissions = $adminUser->getAllPermissions()->flatten()->pluck('name')->toArray();

        return returnData(
            [],
            Response::HTTP_OK,
            [
                'user' => $adminUser,
                'token' => $adminUser->createToken('auth_token', $permissions)->plainTextToken
            ],
            __('custom.auth.logged_in'),
            []
        );
    }
}
