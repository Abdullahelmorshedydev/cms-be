<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\SettingGroupEnum;
use App\Http\Controllers\Dashboard\BaseDashboardController;
use App\Models\Setting;
use App\Services\SettingService;
use App\Traits\MediaHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends BaseDashboardController
{
    use MediaHandler;

    public function __construct(protected SettingService $service)
    {
    }

    public function index()
    {
        try {
            $data = $this->service->index([], [], ['*'], ['id' => 'DESC'], 10)['data'];
            $settings = $data['settings'] ?? [];
            $settingGroups = $data['settingGroups'] ?? [];
            return view('admin.pages.settings.index', compact('settings', 'settingGroups'));
        } catch (\Exception $e) {
            Log::error('Error loading settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->handleError($e, 'dashboard.pages.settings.index', [
                'settings' => [],
                'settingGroups' => [],
            ]);
        }
    }

    public function update($key, Request $request)
    {
        try {
            $data = $request->all();
            $response = $this->service->update($data, $key, 'key', function ($model, $data) {
                if (isset($data['value']['en']) && is_file($data['value']['en'])) {
                    $newImageId = $this->uploadImage($data['value']['en'], $model, $model->getTranslation('label', 'en'));
                    $model->value = [
                        'en' => $newImageId,
                        'ar' => $newImageId
                    ];
                    $model->save();
                }
            });

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating setting', ['key' => $key, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }
}
