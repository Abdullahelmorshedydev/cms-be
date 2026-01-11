<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCategory\StoreServiceCategoryRequest;
use App\Http\Requests\ServiceCategory\UpdateServiceCategoryRequest;
use App\Services\ServiceCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ServiceCategoryController extends Controller
{
    public function __construct(
        protected ServiceCategoryService $service
    ) {
    }

    public function index(Request $request)
    {
        try {
            $data = $request->all();
            unset($data['_token']);
            unset($data['limit']);
            unset($data['page']);

            $response = $this->service->index(
                $data,
                [],
                ['*'],
                ['id' => 'DESC'],
                request()->get('limit', 10)
            );

            return view('admin.pages.service-categories.index', [
                'data' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading service categories', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return handleError($e, 'admin.pages.service-categories.index', [
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
            return view('admin.pages.service-categories.create', ['data' => $this->service->create()['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading service category create page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(StoreServiceCategoryRequest $request)
    {
        try {
            $response = $this->service->store($request->validated());
            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return to_route('dashboard.service-categories.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing service category', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function edit($slug)
    {
        try {
            return view('admin.pages.service-categories.edit', ['data' => $this->service->edit($slug)['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading service category for edit', ['slug' => $slug, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(UpdateServiceCategoryRequest $request, $slug)
    {
        try {
            $response = $this->service->update($request->validated(), $slug);
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.service-categories.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating service category', ['slug' => $slug, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }

    public function destroy($slug)
    {
        try {
            $response = $this->service->destroy('slug', $slug);
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.service-categories.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting service category', ['slug' => $slug, 'error' => $e->getMessage()]);
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

            return to_route('dashboard.service-categories.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting service categories', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }
}
