<?php

namespace App\Http\Controllers\Dashboard\Cms;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrCreatePageRequest;
use App\Models\Page;
use App\Services\PageService;
use App\Services\SectionService;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function __construct(
        private PageService $service,
        private SectionService $sectionService
    ) {
    }

    public function index()
    {
        try {
            return view('admin.pages.cms.pages.index', [
                'pages' => $this->service->getAll()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading CMS pages', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function create()
    {
        try {
            return view('admin.pages.cms.pages.create', [
                'status' => StatusEnum::getAll()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading CMS page create form', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(UpdateOrCreatePageRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->has('is_active'))
                $data['is_active'] = $request->input('is_active', 1);
            $this->service->create($data);

            return redirect()
                ->route('dashboard.cms.pages.index')
                ->with('success', __('response_messages.created', ['model' => 'Page']));
        } catch (\Exception $e) {
            Log::error('Error creating CMS page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function show(Page $page)
    {
        try {
            $sections = $this->sectionService->getPageSections($page->id);
            return view('admin.pages.cms.pages.show', compact('page', 'sections'));
        } catch (\Exception $e) {
            Log::error('Error loading CMS page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function edit(Page $page)
    {
        try {
            return view('admin.pages.cms.pages.edit', [
                'record' => $page,
                'status' => StatusEnum::getAll()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading CMS page for edit', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(UpdateOrCreatePageRequest $request, Page $page)
    {
        try {
            $data = $request->validated();
            if ($request->has('is_active'))
                $data['is_active'] = $request->input('is_active');

            $this->service->update($data, $page);

            return redirect()
                ->route('dashboard.cms.pages.index')
                ->with('success', __('response_messages.updated', ['model' => 'Page']));
        } catch (\Exception $e) {
            Log::error('Error updating CMS page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }

    public function destroy(Page $page)
    {
        try {
            $this->service->delete($page);
            return redirect()
                ->route('dashboard.cms.pages.index')
                ->with('success', __('response_messages.deleted', ['model' => 'Page']));
        } catch (\Exception $e) {
            Log::error('Error deleting CMS page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }
}

