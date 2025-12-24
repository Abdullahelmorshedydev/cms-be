<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SectionType\StoreSectionTypeRequest;
use App\Http\Requests\Admin\SectionType\UpdateSectionTypeRequest;
use App\Services\SectionTypeService;
use App\Resources\Admin\SectionTypeResource;

class SectionTypeController extends Controller
{
  public function __construct(protected SectionTypeService $service)
  {
  }

  public function index()
  {
    $sectionTypes = $this->service->getAll();
    return $this->successResponse(
      ['section_types' => SectionTypeResource::collection($sectionTypes)],
      trans('messages.retrieved_successfully')
    );
  }

  public function store(StoreSectionTypeRequest $request)
  {
    $sectionType = $this->service->create($request->validated());
    return $this->successResponse(
      ['section_type' => SectionTypeResource::make($sectionType)],
      trans('messages.created_successfully')
    );
  }

  public function show($id)
  {
    $sectionType = $this->service->getById($id);
    if (!$sectionType) {
      return $this->notFound();
    }
    return $this->successResponse(
      ['section_type' => SectionTypeResource::make($sectionType)],
      trans('messages.retrieved_successfully')
    );
  }

  public function update(UpdateSectionTypeRequest $request, $id)
  {
    $sectionType = $this->service->getById($id);
    if (!$sectionType) {
      return $this->notFound();
    }
    $updatedSectionType = $this->service->update($sectionType, $request->validated());
    return $this->successResponse(
      ['section_type' => SectionTypeResource::make($updatedSectionType)],
      trans('messages.updated_successfully')
    );
  }

  public function destroy($id)
  {
    $sectionType = $this->service->getById($id);
    if (!$sectionType) {
      return $this->notFound();
    }
    $this->service->delete($sectionType);
    return $this->successResponse(
      [],
      trans('messages.deleted_successfully')
    );
  }
}
