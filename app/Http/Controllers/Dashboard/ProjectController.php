<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $service
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

            return view('admin.pages.projects.index', [
                'data' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading projects', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return handleError($e, 'admin.pages.projects.index', [
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
            return view('admin.pages.projects.create', ['data' => $this->service->create()['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading project create page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            $response = $this->service->store($request->validated());
            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return to_route('dashboard.projects.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing project', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function edit($slug)
    {
        try {
            return view('admin.pages.projects.edit', ['data' => $this->service->edit($slug)['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading project for edit', ['slug' => $slug, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(UpdateProjectRequest $request, $slug)
    {
        try {
            $response = $this->service->update($request->validated(), $slug);
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return to_route('dashboard.projects.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating project', ['slug' => $slug, 'error' => $e->getMessage()]);
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

            return to_route('dashboard.projects.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting service', ['slug' => $slug, 'error' => $e->getMessage()]);
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

            return to_route('dashboard.projects.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting projects', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }
}
