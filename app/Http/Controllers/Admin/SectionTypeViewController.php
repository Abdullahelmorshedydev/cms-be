<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SectionType\StoreSectionTypeRequest;
use App\Http\Requests\Admin\SectionType\UpdateSectionTypeRequest;
use App\Services\SectionTypeService;
use Illuminate\Http\Request;

class SectionTypeViewController extends Controller
{
    public function __construct(protected SectionTypeService $service)
    {
    }

    public function index()
    {
        $sectionTypes = $this->service->getAll();
        return view('admin.pages.cms.section-types.index', compact('sectionTypes'));
    }

    public function create()
    {
        return view('admin.pages.cms.section-types.create');
    }

    public function store(StoreSectionTypeRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return redirect()->route('dashboard.cms.section-types.index')
                ->with('success', trans('messages.created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $sectionType = $this->service->getById($id);
        if (!$sectionType) {
            abort(404);
        }
        return view('admin.pages.cms.section-types.show', compact('sectionType'));
    }

    public function edit($id)
    {
        $sectionType = $this->service->getById($id);
        if (!$sectionType) {
            abort(404);
        }
        return view('admin.pages.cms.section-types.edit', compact('sectionType'));
    }

    public function update(UpdateSectionTypeRequest $request, $id)
    {
        try {
            $sectionType = $this->service->getById($id);
            if (!$sectionType) {
                abort(404);
            }
            $this->service->update($sectionType, $request->validated());
            return redirect()->route('dashboard.cms.section-types.index')
                ->with('success', trans('messages.updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
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
            return redirect()->route('dashboard.cms.section-types.index')
                ->with('success', trans('messages.deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}

