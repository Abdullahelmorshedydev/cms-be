<?php

namespace App\Http\Controllers\Dashboard\Cms;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\SectionService;
use App\Services\SectionTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    public function __construct(
        protected SectionService $service,
        protected SectionTypeService $sectionTypeService
    ) {
    }

    public function index()
    {
        try {
            return view('admin.pages.cms.sections.index', [
                'services' => $this->service->getAll()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading CMS sections', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function create(Request $request)
    {
        try {
            $pageId = $request->input('page_id');
            $page = $pageId ? Page::findOrFail($pageId) : null;
            $sectionTypes = $this->sectionTypeService->getAll();
            $pages = Page::all();

            return view('admin.pages.cms.sections.create', compact('page', 'sectionTypes', 'pages'));
        } catch (\Exception $e) {
            Log::error('Error loading CMS section create form', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(Request $request)
    {
        try {
            $section = $this->service->create($request->all());

            $redirectRoute = $request->input('page_id')
                ? route('dashboard.cms.pages.show', $request->input('page_id'))
                : route('dashboard.cms.sections.index');

            return redirect($redirectRoute)
                ->with('success', trans('messages.created_successfully'));
        } catch (\Exception $e) {
            Log::error('Error creating CMS section', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function show($id)
    {
        try {
            $section = $this->service->getById($id, ['media', 'models', 'sectionType']);
            if (!$section) {
                abort(404);
            }
            return view('admin.pages.cms.sections.show', compact('section'));
        } catch (\Exception $e) {
            Log::error('Error loading CMS section', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function edit($id)
    {
        try {
            $section = $this->service->getById($id, ['media', 'models', 'sectionType']);
            if (!$section) {
                abort(404);
            }
            $sectionTypes = $this->sectionTypeService->getAll();
            $pages = Page::all();

            return view('admin.pages.cms.sections.edit', compact('section', 'sectionTypes', 'pages'));
        } catch (\Exception $e) {
            Log::error('Error loading CMS section for edit', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $section = $this->service->update($request->all(), $id);

            $redirectRoute = $request->input('page_id')
                ? route('dashboard.cms.pages.show', $request->input('page_id'))
                : route('dashboard.cms.sections.index');

            return redirect($redirectRoute)
                ->with('success', trans('messages.updated_successfully'));
        } catch (\Exception $e) {
            Log::error('Error updating CMS section', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $section = $this->service->getById($id);
            if (!$section) {
                abort(404);
            }

            $this->service->deleteMany([$id]);

            return redirect()
                ->route('dashboard.cms.sections.index')
                ->with('success', trans('messages.deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Error deleting CMS section', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }

    public function updateGroup(Request $request)
    {
        try {
            $sections = [];
            foreach ($request->sections as $sectionData) {
                if (isset($sectionData['id'])) {
                    $sections[] = $this->service->update($sectionData, $sectionData['id']);
                } else {
                    $sections[] = $this->service->create(array_merge([
                        'parent_id' => (int) $request->parent_id,
                        'parent_type' => $request->model_type
                    ], $sectionData));
                }
            }

            return redirect()
                ->back()
                ->with('success', trans('messages.updated_successfully'));
        } catch (\Exception $e) {
            Log::error('Error updating CMS section group', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')]);
        }
    }
}


