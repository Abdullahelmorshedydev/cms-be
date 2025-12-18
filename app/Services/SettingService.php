<?php

namespace App\Services;

use App\Enums\SettingGroupEnum;
use App\Repositories\SettingRepository;
use Symfony\Component\HttpFoundation\Response;

class SettingService extends BaseService
{
    public function __construct(SettingRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index($data = [], $with = [], $columns = ['*'], $order = ['id' => 'DESC'], $limit = 10)
    {
        $data = $this->repository->groupByWith($data, 'group', $with, $columns);
        $settings = collect($data)->mapWithKeys(function ($item) {
            return [$item['value'] => $item['values']];
        });
        return returnData(
            [],
            Response::HTTP_OK,
            [
                'settings' => $settings,
                'settingGroups' => SettingGroupEnum::cases()
            ],
            __('custom.messages.retrieved_success')
        );
    }

    public function update($data, $value, $key = 'key', ?callable $callback = null)
    {
        return parent::update($data, $value, $key, $callback);
    }
}
