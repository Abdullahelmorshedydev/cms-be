<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SectionService;
use App\Services\SectionTypeService;
use App\Services\PageService;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SectionViewController extends Controller
{
    public function __construct(
        protected SectionService $service,
        protected SectionTypeService $sectionTypeService,
        protected PageService $pageService
    ) {
    }

    public function index()
    {
        $sections = $this->service->getAll();
        return view('admin.pages.cms.sections.index', compact('sections'));
    }

    public function create()
    {
        $sectionTypes = $this->sectionTypeService->getAll();
        $pages = $this->pageService->getAll();
        $data = [
            'sectionTypes' => $sectionTypes,
            'pages' => $pages,
            'locales' => LaravelLocalization::getSupportedLanguagesKeys(),
        ];
        return view('admin.pages.cms.sections.create', compact('data'));
    }

    public function store(Request $request)
    {
        try {
            $this->service->create($request->all());
            return redirect()->route('dashboard.cms.sections.index')
                ->with('success', trans('messages.created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $section = $this->service->getById($id, ['media', 'sections.media']);
        if (!$section) {
            abort(404);
        }
        return view('admin.pages.cms.sections.show', compact('section'));
    }

    public function edit($id)
    {
        $section = $this->service->getById($id, ['media', 'sections.media']);
        if (!$section) {
            abort(404);
        }
        $sectionTypes = $this->sectionTypeService->getAll();
        $pages = $this->pageService->getAll();
        $data = [
            'record' => $section,
            'sectionTypes' => $sectionTypes,
            'pages' => $pages,
            'locales' => LaravelLocalization::getSupportedLanguagesKeys(),
        ];
        return view('admin.pages.cms.sections.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            return redirect()->route('dashboard.cms.sections.index')
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
            $section = $this->service->getById($id);
            if (!$section) {
                abort(404);
            }
            $this->service->deleteMany([$id]);
            return redirect()->route('dashboard.cms.sections.index')
                ->with('success', trans('messages.deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}


