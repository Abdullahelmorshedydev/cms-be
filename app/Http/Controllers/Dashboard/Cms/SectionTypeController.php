<?php

namespace App\Http\Controllers\Dashboard\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SectionType\StoreSectionTypeRequest;
use App\Http\Requests\Admin\SectionType\UpdateSectionTypeRequest;
use App\Services\SectionTypeService;
use Illuminate\Support\Facades\Log;

class SectionTypeController extends Controller
{
    public function __construct(protected SectionTypeService $service)
    {
    }

    public function index()
    {
        try {
            $sectionTypes = $this->service->getAll();
            return view('admin.pages.cms.section-types.index', compact('sectionTypes'));
        } catch (\Exception $e) {
            Log::error('Error loading CMS section types', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function create()
    {
        try {
            return view('admin.pages.cms.section-types.create');
        } catch (\Exception $e) {
            Log::error('Error loading CMS section type create form', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function store(StoreSectionTypeRequest $request)
    {
        try {
            $sectionType = $this->service->create($request->validated());
            
            return redirect()
                ->route('dashboard.cms.section-types.index')
                ->with('success', trans('messages.created_successfully'));
        } catch (\Exception $e) {
            Log::error('Error creating CMS section type', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    public function show($id)
    {
        try {
            $sectionType = $this->service->getById($id);
            if (!$sectionType) {
                abort(404);
            }
            return view('admin.pages.cms.section-types.show', compact('sectionType'));
        } catch (\Exception $e) {
            Log::error('Error loading CMS section type', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function edit($id)
    {
        try {
            $sectionType = $this->service->getById($id);
            if (!$sectionType) {
                abort(404);
            }
            return view('admin.pages.cms.section-types.edit', compact('sectionType'));
        } catch (\Exception $e) {
            Log::error('Error loading CMS section type for edit', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    public function update(UpdateSectionTypeRequest $request, $id)
    {
        try {
            $sectionType = $this->service->getById($id);
            if (!$sectionType) {
                abort(404);
            }
            
            $updatedSectionType = $this->service->update($sectionType, $request->validated());
            
            return redirect()
                ->route('dashboard.cms.section-types.index')
                ->with('success', trans('messages.updated_successfully'));
        } catch (\Exception $e) {
            Log::error('Error updating CMS section type', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $sectionType = $this->service->getById($id);
            if (!$sectionType) {
                abort(404);
            }
            
            $this->service->delete($sectionType);
            
            return redirect()
                ->route('dashboard.cms.section-types.index')
                ->with('success', trans('messages.deleted_successfully'));
        } catch (\Exception $e) {
            Log::error('Error deleting CMS section type', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }
}


