<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\FormTypeEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Services\FormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FormController extends Controller
{
    public function __construct(
        protected FormService $service
    ) {
    }

    /**
     * Display all form submissions
     */
    public function index(Request $request, $type = null)
    {
        try {
            $requestData = $request->all();
            unset($requestData['_token']);
            unset($requestData['limit']);
            unset($requestData['page']);
            if ($type)
                $requestData['type'] = $type;

            $response = $this->service->index(
                $requestData,
                [],
                ['*'],
                [
                    'id' => 'DESC'
                ],
                request('limit', 10)
            );

            $statistics = $this->service->getStatistics();

            return view('admin.pages.forms.index', [
                'data' => $response,
                'statistics' => $statistics ?? [],
                'types' => FormTypeEnum::toArray(),
                'status' => StatusEnum::cases(),
                'currentType' => $type,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading forms', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return handleError($e, 'admin.pages.forms.index', [
                'data' => [
                    'data' => [
                        'data' => []
                    ],
                    'meta' => []
                ],
                'statistics' => [],
                'types' => FormTypeEnum::toArray(),
                'status' => StatusEnum::cases(),
                'currentType' => $type,
            ]);
        }
    }

    /**
     * Show single form submission
     */
    public function show($id)
    {
        try {
            $response = $this->service->showById($id);

            // ModelNotFoundException will be handled by exception handler, so if we get here, record exists
            if (!isset($response['data']['form']) || !$response['data']['form']) {
                abort(404, __('custom.messages.not_found'));
            }

            return view('admin.pages.forms.show', [
                'form' => $response['data']['form'],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Let ModelNotFoundException bubble up to be handled by exception handler (shows 404 page)
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error loading form', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    /**
     * Mark form as read
     */
    public function markAsRead($id)
    {
        try {
            $response = $this->service->markAsRead($id);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error marking form as read', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')]);
        }
    }

    /**
     * Mark form as unread
     */
    public function markAsUnread($id)
    {
        try {
            $response = $this->service->markAsUnread($id);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error marking form as unread', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')]);
        }
    }

    /**
     * Delete form
     */
    public function destroy($id)
    {
        try {
            $response = $this->service->destroy('id', $id);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting form', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }

    /**
     * Bulk delete forms
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:forms,id'
            ]);

            $response = $this->service->destroyAll($request->ids);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error bulk deleting forms', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }

    /**
     * Bulk mark as read
     */
    public function bulkMarkAsRead(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:forms,id'
            ]);

            $response = $this->service->bulkMarkAsRead($request->ids);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error bulk marking forms as read', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')]);
        }
    }

    /**
     * Export forms to CSV
     */
    public function export(Request $request)
    {
        try {
            $filePath = $this->service->export($request->all());

            if (!file_exists($filePath)) {
                throw new \Exception('Export file not found');
            }

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting forms', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => __('custom.messages.export_failed')]);
        }
    }

}
