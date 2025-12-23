<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    public function __construct(
        protected TagService $service
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

            return view('admin.pages.tags.index', [
                'data' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading tags', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return handleError($e, 'admin.pages.tags.index', [
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
            return view('admin.pages.tags.create', ['data' => $this->service->create()['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading tag create page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(StoreTagRequest $request)
    {
        try {
            $response = $this->service->store($request->validated());
            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return to_route('dashboard.tags.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing tag', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function edit($id)
    {
        try {
            return view('admin.pages.tags.edit', ['data' => $this->service->edit($id)['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading tag for edit', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(UpdateTagRequest $request, $id)
    {
        try {
            $response = $this->service->update($request->validated(), $id);
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.tags.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating tag', ['id' => $id, 'error' => $e->getMessage()]);
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

            return to_route('dashboard.tags.index')->with('message', $message);
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

            return to_route('dashboard.tags.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting tags', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }
}
