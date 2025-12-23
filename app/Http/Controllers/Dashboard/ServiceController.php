<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    public function __construct(
        protected ServiceService $service
    ) {
    }

    public function index(Request $request)
    {
        try {
            $data = $request->all();
            unset($data['_token']);
            unset($data['limit']);

            $response = $this->service->index(
                $data,
                [],
                ['*'],
                ['id' => 'DESC'],
                request()->get('limit', 10)
            );

            return view('admin.pages.services.index', [
                'data' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading services', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return handleError($e, 'admin.pages.services.index', [
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
            return view('admin.pages.services.create', ['data' => $this->service->create()['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading service create page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(StoreServiceRequest $request)
    {
        try {
            $response = $this->service->store($request->validated());
            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return to_route('dashboard.services.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing service', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function edit($id)
    {
        try {
            return view('admin.pages.services.edit', ['data' => $this->service->edit($id)['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading service for edit', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(UpdateServiceRequest $request, $id)
    {
        try {
            $response = $this->service->update($request->validated(), $id);
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.services.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating service', ['id' => $id, 'error' => $e->getMessage()]);
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

            return to_route('dashboard.services.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting service', ['id' => $id, 'error' => $e->getMessage()]);
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

            return to_route('dashboard.services.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting services', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }
}
