<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrCreatePageRequest;
use App\Models\Page;
use App\Services\PageService;
use App\Services\SectionService;
use App\Resources\Admin\PageResource;

class PageController extends Controller
{
    public function __construct(private PageService $service, private SectionService $sectionService)
    {
    }

    public function index()
    {
        $pages = $this->service->getAll();
        return $this->successResponse(
            ['pages' => $pages],
            __('response_messages.retrieved', ['model' => 'Pages'])
        );
    }

    public function store(UpdateOrCreatePageRequest $request)
    {
        $created_page = $this->service->create($request->all());
        return $this->successResponse(
            ['page' => $created_page],
            __('response_messages.created', ['model' => 'Pages'])
        );
    }

    /**
     * Show the specified resource.
     */
    public function show(Page $page)
    {
        $parent = $this->service->getPageSections($page->id);

        return $this->setData(PageResource::make($page))
            ->setMessage(__('response_messages.retrieved', ['model' => 'Page']))
            ->customResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrCreatePageRequest $request, $id)
    {
        $page = $this->service->getById($id);
        if (!$page) {
            return $this->notFound();
        }

        $this->service->update($request->validated(), $page);
        return $this->successResponse(
            ['page' => $page],
            __('response_messages.updated', ['model' => 'Page'])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $page = $this->service->getById($id);
        if (!$page) {
            return $this->notFound();
        }
        $this->service->delete($page);
        return $this->successResponse([], __('response_messages.deleted', ['model' => 'Page']));
    }
}
