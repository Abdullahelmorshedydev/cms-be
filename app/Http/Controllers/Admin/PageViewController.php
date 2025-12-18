<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrCreatePageRequest;
use App\Models\Page;
use App\Services\PageService;
use App\Services\SectionService;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PageViewController extends Controller
{
    public function __construct(
        private PageService $service,
        private SectionService $sectionService
    ) {
    }

    public function index()
    {
        $pages = $this->service->getAll();
        return view('admin.pages.cms.pages.index', compact('pages'));
    }

    public function create()
    {
        $data = [
            'locales' => LaravelLocalization::getSupportedLanguagesKeys(),
            'status' => [
                ['value' => '1', 'lang' => __('custom.words.active')],
                ['value' => '0', 'lang' => __('custom.words.inactive')],
            ],
        ];
        return view('admin.pages.cms.pages.create', compact('data'));
    }

    public function store(UpdateOrCreatePageRequest $request)
    {
        try {
            $this->service->create($request->all());
            return redirect()->route('dashboard.cms.pages.index')
                ->with('success', __('response_messages.created', ['model' => 'Page']));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $page = $this->service->getById($id);
        if (!$page) {
            abort(404);
        }
        $sections = $this->service->getPageSections($page->id);
        return view('admin.pages.cms.pages.show', compact('page', 'sections'));
    }

    public function edit($id)
    {
        $page = $this->service->getById($id);
        if (!$page) {
            abort(404);
        }
        $sections = $this->service->getPageSections($page->id);
        $data = [
            'record' => $page,
            'locales' => LaravelLocalization::getSupportedLanguagesKeys(),
            'status' => [
                ['value' => '1', 'lang' => __('custom.words.active')],
                ['value' => '0', 'lang' => __('custom.words.inactive')],
            ],
        ];
        return view('admin.pages.cms.pages.edit', compact('data', 'sections'));
    }

    public function update(UpdateOrCreatePageRequest $request, $id)
    {
        try {
            $page = $this->service->getById($id);
            if (!$page) {
                abort(404);
            }
            $this->service->update($request->validated(), $page);
            return redirect()->route('dashboard.cms.pages.index')
                ->with('success', __('response_messages.updated', ['model' => 'Page']));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $page = $this->service->getById($id);
            if (!$page) {
                abort(404);
            }
            $this->service->delete($page);
            return redirect()->route('dashboard.cms.pages.index')
                ->with('success', __('response_messages.deleted', ['model' => 'Page']));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}


