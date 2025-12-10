<?php

namespace App\Repositories;

use App\Models\CmsSection;
use App\Models\Page;

class SectionRepository extends BaseRepository
{
    protected function model(): string
    {
        return CmsSection::class;
    }
    public function getSectionsWithoutParent()
    {
        return $this->model::whereNull('parent_id')->whereNotNull('parent_type')->get();
    }
    public function updateSection($data, $id)
    {
        $record = $this->findOne($id);
        $record->update($data);
        return $record;
    }
    public function deleteParentSections(int $parent_id, array $sections_id)
    {
        $this->model->where($this->model->getParentIdentifier(), $parent_id)->whereIn('id', $sections_id)->delete();
    }
    public function delete($id)
    {
        $record = $this->findOne($id);
        $this->deleteSectionsWithRelations(collect([$record]));
    }
    public function deletePageSections($page_id)
    {
        $this->deleteMany($this->model->where('parent_type', Page::class)->where('parent_id', $page_id)->pluck('id')->toArray());
    }
    public function getPageSection($page_id, $section_name)
    {
        return $this->model->where('parent_type', Page::class)->where('parent_id', $page_id)->where('name', $section_name)->first();
    }
    public function deleteMany($ids)
    {
        $sections = $this->model->whereIn('id', $ids)->orWhereIn('section_id', $ids)->get();
        $this->deleteSectionsWithRelations($sections);
    }
    public function deleteSectionsWithRelations($sections)
    {
        foreach ($sections as $section) {
            $this->deleteChildSections($section);
            $section->media()->delete();
            $section->models()->delete();
            $section->delete();
        }
    }

    private function deleteChildSections($section)
    {
        $subSections = $section->sections()->get();
        foreach ($subSections as $subSection) {
            $this->deleteChildSections($subSection);
            $subSection->media()->delete();
            $subSection->models()->delete();
            $subSection->delete();
        }
    }
    public function getSectionParent($parentClass, $model_id)
    {
        return $parentClass::withoutGlobalScopes()->findOrFail($model_id);
    }

}
