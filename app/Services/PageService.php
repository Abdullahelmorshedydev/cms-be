<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\Validator;

class PageService
{
    public function __construct(protected PageRepository $repository, protected SectionService $sectionService)
    {
    }

    public function getAll()
    {
        $pages = $this->repository->getAll()->toBase();
        return $pages;
    }
    public function getAllWithModelSections()
    {
        $pages = $this->repository->getAll()->toBase();
        $pages = $pages->merge($this->sectionService->getModelSections()->values()->toBase());
        return $pages;
    }
    public function getByName($name)
    {
        $page = $this->repository->findOneBy([
            'name' => $name,
        ]);
        return $page;
    }
    public function getBySlug($slug)
    {
        $page = $this->repository->findOneBy([
            'slug' => $slug,
        ]);
        return $page;
    }
    public function getById($id)
    {
        $page = $this->repository->findOne($id);
        return $page;
    }

    function validateGetPageSection($data)
    {
        return Validator::make($data, [
            'page_id' => 'required|exists:pages,id',
            'section_name' => 'required'
        ]);
    }
    public function getPageSectionByName($data)
    {
        $this->validateGetPageSectionByName($data)->validate();
        return $this->sectionService->getPageSectionByName($data['page_name'], $data['section_name']);
    }

    function validateGetPageSectionByName($data)
    {
        return Validator::make($data, [
            'page_name' => 'required|exists:pages,name',
            'section_name' => 'required'
        ]);
    }

    public function getPageSection($data)
    {
        $this->validateGetPageSection($data)->validate();
        return $this->sectionService->getPageSection($data['page_id'], $data['section_name']);
    }
    public function getPageSections($page_id)
    {
        return $this->sectionService->getPageSections($page_id);
    }

    public function getSectionsNames(string $pageName): array
    {
        $page = $this->repository->findOneBy([
            'name' => $pageName,
        ]);
        if (!$page) {
            return [];
        }
        return [
            'id' => $page->id,
            'name' => $page->name,
            'sections' => $page->sections->map(fn($section) => [
                'id' => $section->id,
                'name' => $section->name,
                'content' => $section->content,
                'button_text' => $section->button_text,
                'button_type' => $section->button_type,
                'button_data' => $section->button_data,
            ])->toArray(),
        ];
    }

    public function create($data)
    {

        return $this->repository->create($data);
    }
    public function update($data, $page)
    {

        return $this->repository->update($page, $data);
    }
    public function delete($page)
    {

        return $this->repository->delete($page);
    }

}
