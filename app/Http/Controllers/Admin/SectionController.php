<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SectionService;
use App\Resources\Admin\SectionResource;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function __construct(protected SectionService $service)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $section = $this->service->create($request->all());
        return $this->successResponse(
            [
                'section' => SectionResource::make($section),
            ],
            trans('messages.created_successfully')
        );

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $updatedSection = $this->service->update($request->all(), $id);
        return $this->successResponse(
            ['section' => SectionResource::make($updatedSection)],
            trans('messages.updated_successfully')
        );
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $section = $this->service->getById($id);
        if (!$section)
            return $this->notFound();
        $this->service->deleteMany([$id]);
        return $this->successResponse(
            [],
            trans('messages.deleted_successfully'),
        );
    }
    public function updateGroup(Request $request)
    {
        DB::beginTransaction();
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
        DB::commit();
        return $this->successResponse($sections, trans('messages.updated_successfully'));
    }
}
