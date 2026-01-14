<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {
    }

    public function index()
    {
        $response = $this->settingService->getWithoutGrouped([], ['image'], ['*'], ['id' => 'DESC'], false);
        return $this->setData($response['data'])->setCode($response['code'])->setMessage($response['message'])->customResponse();
    }

    public function show($key)
    {
        $response = $this->settingService->show('key', $key, ['image']);
        return $this->setData($response['data'])->setCode($response['code'])->setMessage($response['message'])->customResponse();
    }
}
