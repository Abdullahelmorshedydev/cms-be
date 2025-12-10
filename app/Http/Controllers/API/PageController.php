<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\PageService;
use App\Transformers\API\PageResource;
use App\Transformers\API\SectionResource;

class PageController extends Controller
{
    public function __construct(private PageService $service)
    {
    }

    public function index()
    {
        return $this->successResponse(
            ['pages' => $this->service->getAllWithModelSections()],
            __('response_messages.retrieved', ['model' => 'Pages'])
        );
    }

    /**

     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $page = $this->service->create($request->all());
        return $this->successResponse(
            ['page' => PageResource::make($page)],
            trans('messages.created_successfully')
        );

    }

    /**
     * Show the specified resource.
     */
    public function show($slug)
    {
        $page = $this->service->getBySlug($slug);
        if (!$page)
            return $this->notFound();
        $page = PageResource::make($page);
        return $this->successResponse(
            ['page' => $page],
            __('response_messages.retrieved', ['model' => 'Page' . ' ' . $page->name])
        );
    }

    public function getPageSection($page_id, $section_name)
    {
        if (request()->has('get_by_name') && request()->get_by_name == 1) {
            $data = [
                'page_name' => $page_id,
                'section_name' => $section_name
            ];
            $section = $this->service->getPageSectionByName($data);
            if (!$section)
                return $this->notFound();
            return $this->successResponse(
                ['section' => SectionResource::make($section)],
                trans('messages.success')
            );

        }

        $data = [
            'page_id' => $page_id,
            'section_name' => $section_name
        ];

        $section = $this->service->getPageSection($data);

        if (!$section)
            return $this->notFound();
        return $this->successResponse(
            ['section' => SectionResource::make($section)],
            trans('messages.success')
        );
    }

    public function getSectionsNames(Request $request)
    {
        if (!$request->page_name) {
            return $this->errorResponse(
                trans('response_messages.page_name_required'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $sections = $this->service->getSectionsNames($request->page_name);
        if (!$sections) {
            return $this->notFound();
        }

        return $this->successResponse(
            ['section' => $sections],
            trans('messages.success')
        );

    }
}
