<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $service,
        protected MediaService $mediaService
    ) {
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

            return handleError($e, 'admin.pages.settings.index', [
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
                    $newImage = $this->mediaService->uploadImage($data['value']['en'], $model, $model->getTranslation('label', 'en'));
                    $model->value = [
                        'en' => $newImage,
                        'ar' => $newImage
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
