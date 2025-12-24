<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SectionRepository;
use App\Services\SectionService;
use App\Resources\API\SectionResource;

class SectionController extends Controller
{
    public function __construct(protected SectionService $service, protected SectionRepository $repository)
    {
    }
    public function index()
    {
        $sections = SectionResource::collection($this->service->getAll());
        return $this->successResponse(['sections' => $sections], trans('response_messages.success'));
    }

    /**
     * Show the specified resource.
     */
    public function show(int $id)
    {
        $section = $this->service->getById($id);
        if (!$section)
            return $this->notFound();
        return $this->successResponse(
            ['section' => SectionResource::make($section)],
            trans('response_messages.success')
        );
    }

    public function getSectionTypes()
    {
        $types = $this->service->getSectionTypes();
        return $this->successResponse(['types' => $types], trans('response_messages.success'));
    }
}
