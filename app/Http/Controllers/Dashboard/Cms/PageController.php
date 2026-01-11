<?php

namespace App\Http\Controllers\Dashboard\Cms;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrCreatePageRequest;
use App\Models\Page;
use App\Services\PageService;
use App\Services\SectionTypeService;
use App\Services\SectionService;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function __construct(
        private PageService $service,
        private SectionService $sectionService,
        private SectionTypeService $sectionTypeService
    ) {
    }

    public function index()
    {
        try {
            return view('admin.pages.cms.pages.index', [
                'pages' => $this->service->getAll(),
                'status' => StatusEnum::getAll()
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
            $this->service->create($request->validated());
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
            return view('admin.pages.cms.pages.show', [
                'page' => $page,
                'sections' => $sections
            ]);
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
            $this->service->update($request->validated(), $page);
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

    public function editSections(Page $page)
    {
        try {
            // Get sections WITHOUT any automatic eager loading
            $sections = $this->sectionService->getPageSections($page->id);

            // Now explicitly load ONLY what we need, with strict depth control
            // Load only ONE level deep to prevent infinite recursion
            $sections->load([
                'sectionTypes',
                'images' => function ($query) {
                    $query->orderBy('order');
                },
                'videos' => function ($query) {
                    $query->orderBy('order');
                },
                'icon',
                'sections' => function ($query) {
                    // Only load direct children, no deeper
                    $query->orderBy('order');
                },
            ]);

            // Load relationships for subsections (one level only)
            // DO NOT load sections.sections - this would cause infinite recursion
            foreach ($sections as $section) {
                if ($section->relationLoaded('sections') && $section->sections->isNotEmpty()) {
                    $section->sections->load([
                        'sectionTypes',
                        'images' => function ($query) {
                            $query->orderBy('order');
                        },
                        'videos' => function ($query) {
                            $query->orderBy('order');
                        },
                        'icon',
                        // DO NOT load sections here - would cause infinite recursion
                    ]);
                }
            }

            // Get all available section types
            $sectionTypes = $this->sectionTypeService->getAll();

            return view('admin.pages.cms.pages.sections.edit', [
                'page' => $page,
                'sections' => $sections,
                'sectionTypes' => $sectionTypes,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading page sections for edit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'page_id' => $page->id
            ]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }
}

