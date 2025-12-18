<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BaseDashboardController;
use App\Http\Requests\Page\StorePageRequest;
use App\Http\Requests\Page\UpdatePageRequest;
use App\Services\PageService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PageController extends BaseDashboardController
{
    public function __construct(
        protected PageService $service
    ) {
    }

    public function create()
    {
        try {
            return view('admin.pages.pages.create', ['data' => $this->service->create()['data']]);
        } catch (\Exception $e) {
            Log::error('Error loading page create page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(StorePageRequest $request)
    {
        try {
            $data = $request->validated();
            $sectionsData = $data['sections'] ?? [];
            unset($data['sections']);
            $pageData = $data;
            $response = $this->service->createPageWithSections($pageData, $sectionsData);
            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function edit($slug)
    {
        try {
            $response = $this->service->edit($slug);

            // ModelNotFoundException will be handled by exception handler, so if we get here, record exists
            if (!isset($response['data']) || empty($response['data'])) {
                abort(404, __('custom.messages.not_found'));
            }

            return view('admin.pages.pages.edit', ['data' => $response['data']]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Let ModelNotFoundException bubble up to be handled by exception handler (shows 404 page)
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error loading page for edit', ['slug' => $slug, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(UpdatePageRequest $request, $slug)
    {
        try {
            $data = $request->validated();
            $sectionsData = isset($data['sections']) ? $data['sections'] : [];
            $deletedSections = isset($data['deleted_sections_ids']) ? $data['deleted_sections_ids'] : [];
            $deletedMedia = isset($data['deleted_media_ids']) ? $data['deleted_media_ids'] : [];
            unset($data['sections']);
            unset($data['deleted_sections_ids']);
            unset($data['deleted_media_ids']);
            $pageData = $data;
            $response = $this->service->updatePageWithSections($slug, $pageData, $sectionsData, $deletedSections, $deletedMedia);
            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating page', ['slug' => $slug, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }
}
