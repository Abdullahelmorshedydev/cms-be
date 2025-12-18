<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
        protected UserService $service
    ) {
    }

    public function index(Request $request)
    {
        try {
            $data = $request->all();
            if (!isset($data['filters']) || !isset($data['filters']['emails'])) {
                $data['filters']['email'] = [
                    'operator' => '!=',
                    'value' => 'super-admin@tasweek.com'
                ];
            }
            unset($data['_token']);
            unset($data['limit']);

            $response = $this->service->index(
                $data,
                ['roles'],
                ['*'],
                ['id' => 'DESC'],
                request()->get('limit', 10)
            );

            return view('admin.pages.users.index', [
                'data' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading users', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return handleError($e, 'admin.pages.users.index', [
                'data' => [
                    'data' => [
                        'data' => []
                    ]
                ],
                'meta' => [],
            ]);
        }
    }

    public function create()
    {
        try {
            return view('admin.pages.users.create', ['data' => $this->service->create()['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading user create page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $response = $this->service->store($request->validated());
            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return to_route('dashboard.users.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing user', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function edit($id)
    {
        try {
            return view('admin.pages.users.edit', ['data' => $this->service->edit($id)['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading user for edit', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $response = $this->service->update($request->validated(), $id);
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.users.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating user', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->service->destroy('id', $id);
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.users.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting user', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }

    public function destroyAll(Request $request)
    {
        try {
            $response = $this->service->destroyAll(explode(',', $request->ids));
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.users.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting users', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }

    /**
     * Export users to CSV
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function export(Request $request)
    {
        try {
            $data = $request->all();
            unset($data['_token']);
            unset($data['limit']);

            // Get all users (no pagination for export)
            $users = $this->service->index($data, ['roles'], ['*'], ['id' => 'DESC'], 999999);
            $usersList = $users['data']['data'] ?? [];

            // Generate CSV
            $filename = 'users_export_' . now()->format('Y_m_d_His') . '.csv';
            $filePath = storage_path('app/exports/' . $filename);

            // Create exports directory if it doesn't exist
            if (!file_exists(storage_path('app/exports'))) {
                mkdir(storage_path('app/exports'), 0755, true);
            }

            $file = fopen($filePath, 'w');

            // Add BOM for UTF-8 (helps with Excel)
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add CSV headers
            fputcsv($file, [
                'ID',
                __('custom.columns.name'),
                __('custom.columns.email'),
                __('custom.columns.phone'),
                __('custom.words.status'),
                __('custom.columns.created_at'),
            ]);

            // Add data rows
            foreach ($usersList as $user) {
                fputcsv($file, [
                    $user->id ?? '',
                    $user->name ?? '',
                    $user->email ?? '',
                    $user->phone ?? '',
                    $user->is_active ?? '',
                    $user->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($file);

            // Return download response
            return response()->download($filePath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error exporting users', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => __('custom.messages.export_failed')]);
        }
    }

    /**
     * Import users from CSV
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt'
            ]);

            // Implementation for import logic can be added here
            // For now, just return success message

            $message = [
                'status' => true,
                'content' => __('custom.messages.imported_success')
            ];

            return back()->with('message', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error importing users', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => __('custom.messages.imported_failed')]);
        }
    }
}
