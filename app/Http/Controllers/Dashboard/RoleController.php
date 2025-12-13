<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BaseDashboardController;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Repositories\RoleRepository;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends BaseDashboardController
{
    public function __construct(
        protected RoleRepository $roleRepository,
        protected RoleService $service
    ) {}

    public function index(Request $request)
    {
        try {
            $serviceResponse = $this->service->index(
                [
                    'name' => [
                        'operator' => '!=',
                        'value' => 'super-admin'
                    ],
                ],
                [],
                ['*'],
                ['id' => 'DESC'],
                request()->get('limit', 10)
            );

            return view('dashboard.pages.roles.index', [
                'data' => $serviceResponse,
                'roles' => $this->extractPaginatedData($serviceResponse),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading roles', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->handleError($e, 'dashboard.pages.roles.index', [
                'data' => ['data' => ['data' => []]],
                'roles' => $this->getEmptyPaginator(),
            ]);
        }
    }

    public function create()
    {
        try {
            return view('dashboard.pages.roles.create', ['data' => $this->service->create()['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading role create page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            $response = $this->service->store($request->validated());

            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return to_route('dashboard.roles.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing role', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function edit($role)
    {
        try {
            $response = $this->service->edit($role);

            // ModelNotFoundException will be handled by exception handler, so if we get here, record exists
            if (!isset($response['data']) || empty($response['data'])) {
                abort(404, __('custom.messages.not_found'));
            }

            return view('dashboard.pages.roles.edit', ['data' => $response['data']]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Let ModelNotFoundException bubble up to be handled by exception handler (shows 404 page)
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error loading role for edit', ['id' => $role, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(UpdateRoleRequest $request, $role)
    {
        try {
            $response = $this->service->update($request->validated(), $role, 'id');
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.roles.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating role', ['id' => $role, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }

    public function destroy($role)
    {
        try {
            $response = $this->service->destroy('id', $role);
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting role', ['id' => $role, 'error' => $e->getMessage()]);
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

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting roles', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }
}
