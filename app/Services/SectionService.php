<?php

namespace App\Services;

use App\Enums\SectionButtonTypeEnum;
use App\Enums\SectionFieldEnum;
use App\Enums\SectionModelTypeEnum;
use App\Enums\SectionParentTypeEnum;
use App\HelperClasses\CmsHelpers;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Project;
use App\Models\SectionType;
use App\Models\Service;
use App\Models\Tag;
use App\Repositories\PageRepository;
use App\Repositories\SectionModelRepository;
use App\Repositories\SectionRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SectionService
{
    public function __construct(
        protected SectionRepository $repository,
        protected PageRepository $pageRepository,
        private SectionModelRepository $sectionModelRepository,
        protected MediaService $mediaService
    ) {
    }
    public function getPageSections($page_id)
    {
        // Get sections WITHOUT any automatic eager loading
        // Query directly to bypass any $with relationships
        $sections = $this->repository->getModel()
            ->where('parent_type', Page::class)
            ->where('parent_id', $page_id)
            ->orderBy('order', 'ASC')
            ->get();

        return $sections;
    }

    public function create($data)
    {
        $this->validateType($data)->validate();
        $data = $this->prepareSectionData($data);
        $this->validateSection($data)->validate();
        DB::beginTransaction();
        try {
            $section = $this->repository->create($data);
            $sectionType = SectionType::where('slug', $data['type'])->first();

            if ($this->hasField($sectionType, SectionFieldEnum::IMAGE->value))
                $this->addMultipleImagesToSection($section, $data['images'] ?? [], $data['removed_ids'] ?? []);

            if ($this->hasField($sectionType, SectionFieldEnum::GALLERY->value))
                $this->addMultipleImagesToSection($section, $data['images'] ?? [], $data['removed_ids'] ?? []);

            if ($this->hasField($sectionType, SectionFieldEnum::ICON->value))
                $this->uploadIcon($section, $data['icon'] ?? null);

            if ($this->hasField($sectionType, SectionFieldEnum::VIDEO->value))
                $this->addMultipleVideosToSection($section, $data['videos'] ?? [], $data['images'] ?? [], $data['remove_videos_ids'] ?? [], $data['remove_images_ids'] ?? []);

            if ($this->hasChildSection($data))
                $this->addMultipleChildSection($data['sub_sections'], $section->id);

            if ($this->hasModel($data)) {
                $section_model = ['section' => $section, 'model' => $data['model'] ?? null, 'model_data' => $data['model_data'] ?? []];
                $this->addModelToSection($section_model);
            }
            DB::commit();
            $section->refresh();

            return $section;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function getAll()
    {
        return $this->repository->getAll();
    }
    public function getById($id, $relations = [])
    {
        return $this->repository->findOne($id, $relations);
    }
    public function getModelSections()
    {
        return $this->repository->getSectionsWithoutParent()->groupBy('parent_type');
    }
    public function getPageSection($page_id, $section_name)
    {
        return $this->repository->getPageSection($page_id, $section_name);
    }
    public function getPageSectionByName($page_name, $section_name)
    {
        $page_id = $this->pageRepository->findOneBy(['name' => $page_name])->id;
        return $this->repository->getPageSection($page_id, $section_name);
    }
    public function update($data, $id, $type = 'parent')
    {
        $oldSectionData = $this->repository->findOne($id);
        if (!$oldSectionData)
            throw new \InvalidArgumentException("Section with id [$id] not found");
        $this->validateType($data)->validate();
        $data['id'] = $id;
        if ($type === 'parent')
            $data['parent_id'] = $oldSectionData->parent_id;
        elseif ($type === 'child')
            $data['section_id'] = $oldSectionData->section_id;

        $data = $this->prepareSectionData($data);
        $this->validateSection($data, true)->validate();
        $section = $this->repository->updateSection($data, $id);
        $section->refresh();
        if ($this->hasDeletedRelations($data))
            $this->deleteSectionRelations($section->id, $data['deleted_models'] ?? []);
        else if ($this->deleteAllRelations($data))
            $this->removeAllSectionRelations($section);
        if ($this->hasModel($data))
            $this->addModelToSection(['model' => $data['model'] ?? null, 'model_data' => $data['model_data'] ?? [], 'section' => $section]);
        $sectionType = SectionType::where('slug', $data['type'])->first();

        if ($this->hasField($sectionType, SectionFieldEnum::ICON->value))
            $this->uploadIcon($section, $data['icon'] ?? null);

        if ($this->hasField($sectionType, SectionFieldEnum::IMAGE->value))
            $this->addMultipleImagesToSection($section, $data['images'] ?? [], $data['removed_ids'] ?? []);

        if ($this->hasField($sectionType, SectionFieldEnum::GALLERY->value))
            $this->addMultipleImagesToSection($section, $data['images'] ?? [], $data['removed_ids'] ?? []);

        if ($this->hasField($sectionType, SectionFieldEnum::VIDEO->value))
            $this->addMultipleVideosToSection($section, $data['videos'] ?? [], $data['images'] ?? [], $data['remove_videos_ids'] ?? [], $data['remove_images_ids'] ?? []);
        if ($this->hasChildSection($data))
            $this->addMultipleChildSection($data['sub_sections'], $section->id);
        $section->refresh();
        return $section;
    }
    public function delete($id)
    {
        $section = $this->repository->findOne($id);
        if ($section)
            $this->removeAllMediaForSection($section);
        $this->repository->delete($id);
    }
    public function deleteMany(array $sections_ids)
    {
        foreach ($sections_ids as $sectionId) {
            $section = $this->repository->findOne($sectionId);
            if ($section)
                $this->removeAllMediaForSection($section);
        }
        $this->repository->deleteMany($sections_ids);
    }
    protected function hasField(?SectionType $sectionType, string $field)
    {
        return $sectionType && in_array($field, $sectionType->fields ?? []);
    }
    protected function hasChildSection($data)
    {
        return isset($data['sub_sections']);
    }
    protected function addMultipleImagesToSection($section, $images, $removeIds): void
    {
        $this->mediaService->uploadImages($images, $section, $section->name, $removeIds);
    }
    protected function uploadIcon($section, $icon)
    {
        $this->mediaService->uploadIcon($icon, $section, $section->name);
    }
    protected function addMultipleVideosToSection($section, $videos, $images, $removeVideosIds, $removeImagesIds): void
    {
        // Only process videos if provided or there are removals
        if (!empty($videos) || !empty($removeVideosIds)) {
            $this->mediaService->uploadVideos($videos ?? [], $section, $section->name, $removeVideosIds ?? []);
        }

        // Handle video poster images separately
        if (!empty($images) || !empty($removeImagesIds)) {
            // Auto-remove old poster images with the same collection_name when new ones are uploaded
            $posterCollectionNames = ['video_poster_desktop', 'video_poster_mobile'];
            $autoRemoveIds = [];

            if (!empty($images)) {
                foreach ($images as $image) {
                    if (isset($image['collection_name']) && in_array($image['collection_name'], $posterCollectionNames)) {
                        // Find and collect IDs of old poster images with the same collection_name
                        $oldPosters = $section->images()
                            ->where('collection_name', $image['collection_name'])
                            ->where('device', $image['device'] ?? 'desktop')
                            ->pluck('id')
                            ->toArray();
                        $autoRemoveIds = array_merge($autoRemoveIds, $oldPosters);
                    }
                }
            }

            // Merge auto-removed IDs with explicitly provided remove IDs
            $allRemoveIds = array_unique(array_merge($removeImagesIds ?? [], $autoRemoveIds));

            $this->addMultipleImagesToSection($section, $images ?? [], $allRemoveIds);
        }
    }
    protected function addMultipleChildSection($sections, $parent_id)
    {
        foreach ($sections as $child_section_data) {
            $child_section_data['section_id'] = $parent_id;
            if (isset($child_section_data['parent_id']))
                unset($child_section_data['parent_id']);
            if (isset($child_section_data['parent_type']))
                unset($child_section_data['parent_type']);
            if (!isset($child_section_data['id']))
                $this->create($child_section_data);
            else
                $this->update($child_section_data, $child_section_data['id'], 'child');
        }
    }
    private function hasModel($data)
    {
        return $data['has_relation'] ?? false;
    }
    private function hasButton($data)
    {
        return isset($data['has_button']) && $data['has_button'];
    }
    private function hasDeletedRelations($data)
    {
        return isset($data['deleted_models']) && is_array($data['deleted_models']) && count($data['deleted_models']) > 0;
    }
    private function deleteAllRelations($data)
    {
        return isset($data['delete_all_models']) && $data['delete_all_models'];
    }
    private function addModelToSection($data)
    {
        if (empty($data['model']) || empty($data['model_data']) || !is_array($data['model_data'])) {
            return;
        }

        $model_type = $this->getSectionModel($data['model']);

        // Validate model_type is valid before proceeding
        if (empty($model_type) || !is_string($model_type) || !class_exists($model_type)) {
            return;
        }

        $model_data['model_type'] = $model_type;
        $model_data['section_id'] = $data['section']->id;
        $this->deleteRemovedModel($data, $model_type);

        foreach ($data['model_data'] as $model) {
            if (!isset($model['model_id']) || !isset($model['order'])) {
                continue;
            }
            $model_data['model_id'] = $model['model_id'];
            $model_data['order'] = $model['order'];
            $old_model = $this->getModel($data['section'], $model_data['model_id']);
            if ($old_model)
                $this->sectionModelRepository->update($old_model, ['order' => $model['order']]);
            else
                $this->sectionModelRepository->create($model_data);
        }
    }
    private function deleteRemovedModel($data, $model_type)
    {
        // Validate model_type is a valid class
        if (empty($model_type) || !is_string($model_type) || !class_exists($model_type)) {
            return;
        }

        $section = $data['section'];
        $old_models = $section->models;

        // Ensure model_data is an array
        if (empty($data['model_data']) || !is_array($data['model_data'])) {
            return;
        }

        $ids = collect($data['model_data'])->pluck('model_id')->filter()->toArray();
        if (empty($ids)) {
            return;
        }

        $new_models = $model_type::whereIn('id', $ids)->pluck('id');
        $removed_models = array_diff($old_models->pluck('model_id')->toArray(), $new_models->toArray());
        if ($removed_models) {
            $itemsToDelete = [];
            foreach ($removed_models as $id) {
                $itemsToDelete[] = ['model_type' => $model_type, 'model_id' => $id];
            }
            $this->sectionModelRepository->deleteRelation($section->id, $itemsToDelete);
        }
    }
    private function deleteSectionRelations($section_id, $deleted_models)
    {
        $this->sectionModelRepository->deleteRelation($section_id, $this->getDeletedModels($deleted_models));
    }
    private function removeAllSectionRelations($section)
    {
        $section->models()->delete();
    }
    private function getModel($section, $model_id)
    {
        return $section->models->where('model_id', $model_id)?->first();
    }
    private function getSectionModel($section)
    {
        return match ($section) {
            (new Page())->getTable() => Page::class,
            (new Service())->getTable() => Service::class,
            (new Project())->getTable() => Project::class,
            (new Tag())->getTable() => Tag::class,
            (new Partner())->getTable() => Partner::class,
            default => null,
        };
    }
    private function getDeletedModels($deletedModels)
    {
        return collect($deletedModels)->map(function ($deletedModel) {
            return [
                'model_type' => match ($deletedModel['model_type']) {
                    (new Service())->getTable() => Service::class,
                    (new Project())->getTable() => Project::class,
                    (new Tag())->getTable() => Tag::class,
                    (new Partner())->getTable() => Partner::class,
                    default => $deletedModel['model_type'],
                },
                'model_id' => $deletedModel['model_id'],
            ];
        })->values()->toArray();
    }
    public function getSectionModelTypeClass($model_type)
    {
        return SectionParentTypeEnum::fromKey($model_type);
    }
    private function prepareSectionData(array $data): array
    {
        $data['parent_id'] = $data['parent_id'] ?? request('page_id');
        $data['parent_type'] = CmsHelpers::convertToClassName($data['parent_type'] ?? 'page');
        $data['name'] = Str::slug($data['name']);
        if ($this->hasButton($data)) {
            foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
                $data['button_text'][$locale] = $data["button_text"][$locale] ?? null;
            }
        } else {
            foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
                $data['button_text'][$locale] = null;
            }
            $data['button_type'] = null;
        }

        // Transform image data structure from form format to MediaService format
        $data = $this->prepareImageData($data);

        // Transform video data structure
        $data = $this->prepareVideoData($data);

        $content = $this->prepareSectionContent($data);
        if ($content)
            $data['content'] = $content;
        return $data;
    }

    /**
     * Transform image form data to MediaService format
     * Converts: image[desktop], image[mobile] -> images array with device and collection_name
     */
    private function prepareImageData(array $data): array
    {
        $images = [];

        // Handle desktop image
        if (isset($data['image']['desktop']) && $data['image']['desktop'] instanceof UploadedFile) {
            $images[] = [
                'file' => $data['image']['desktop'],
                'device' => 'desktop',
                'collection_name' => 'image_desktop',
                'order' => 0,
            ];
        }

        // Handle mobile image
        if (isset($data['image']['mobile']) && $data['image']['mobile'] instanceof UploadedFile) {
            $images[] = [
                'file' => $data['image']['mobile'],
                'device' => 'mobile',
                'collection_name' => 'image_mobile',
                'order' => 1,
            ];
        }

        // If images array already exists (from other sources), merge them
        if (!empty($images)) {
            $data['images'] = array_merge($data['images'] ?? [], $images);
        }

        return $data;
    }

    /**
     * Transform video form data to MediaService format
     */
    private function prepareVideoData(array $data): array
    {
        $videos = [];
        $videoPosters = [];

        // Handle desktop video
        if (isset($data['video']['desktop']) && $data['video']['desktop'] instanceof UploadedFile) {
            $videos[] = [
                'file' => $data['video']['desktop'],
                'device' => 'desktop',
                'collection_name' => 'video_desktop',
                'order' => 0,
            ];
        }

        // Handle mobile video
        if (isset($data['video']['mobile']) && $data['video']['mobile'] instanceof UploadedFile) {
            $videos[] = [
                'file' => $data['video']['mobile'],
                'device' => 'mobile',
                'collection_name' => 'video_mobile',
                'order' => 1,
            ];
        }

        // Handle video posters
        if (isset($data['video']['poster']['desktop']) && $data['video']['poster']['desktop'] instanceof UploadedFile) {
            $videoPosters[] = [
                'file' => $data['video']['poster']['desktop'],
                'device' => 'desktop',
                'collection_name' => 'video_poster_desktop',
                'order' => 0,
            ];
        }

        if (isset($data['video']['poster']['mobile']) && $data['video']['poster']['mobile'] instanceof UploadedFile) {
            $videoPosters[] = [
                'file' => $data['video']['poster']['mobile'],
                'device' => 'mobile',
                'collection_name' => 'video_poster_mobile',
                'order' => 1,
            ];
        }

        // Merge with existing arrays
        if (!empty($videos)) {
            $data['videos'] = array_merge($data['videos'] ?? [], $videos);
        }

        if (!empty($videoPosters)) {
            $data['images'] = array_merge($data['images'] ?? [], $videoPosters);
        }

        return $data;
    }
    private function prepareSectionContent(array $data)
    {
        $content = [];
        $sectionType = SectionType::where('slug', $data['type'])->first();

        if ($this->hasField($sectionType, SectionFieldEnum::TITLE->value)) {
            $content['title'] = $this->prepareContentTranslator($data, 'title');
        }
        if ($this->hasField($sectionType, SectionFieldEnum::SUBTITLE->value)) {
            $content['subtitle'] = $this->prepareContentTranslator($data, 'subtitle');
        }
        if ($this->hasField($sectionType, SectionFieldEnum::DESCRIPTION->value)) {
            $content['description'] = $this->prepareContentTranslator($data, 'description');
        }
        if ($this->hasField($sectionType, SectionFieldEnum::SHORT_DESCRIPTION->value)) {
            $content['short_description'] = $this->prepareContentTranslator($data, 'short_description');
        }
        if ($this->hasField($sectionType, SectionFieldEnum::CONTENT->value)) {
            $content['content'] = $this->prepareContentTranslator($data, 'content');
        }

        if ($this->hasField($sectionType, SectionFieldEnum::BUTTONS->value)) {
            $buttons = $data['content']['buttons'] ?? [];
            $processedButtons = [];
            foreach ($buttons as $button) {
                $processedButton = [
                    'url' => $button['url'] ?? '#',
                    'type' => $button['type'] ?? null,
                    'label' => []
                ];
                foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
                    $processedButton['label'][$locale] = $button['label'][$locale] ?? '';
                }
                $processedButtons[] = $processedButton;
            }
            $content['buttons'] = $processedButtons;
        }

        return $content;
    }
    private function prepareContentTranslator(array $data, string $key)
    {
        $translation = [];
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $translation[$locale] = $data['content'][$key][$locale] ?? '';
        }
        return $translation;
    }
    private function validateSection(array $data, bool $isUpdate = false)
    {
        $parentFormId = $data['parentFormId'] ?? null;
        $formId = $data['formId'] ?? null;
        $rules = [
            'name' => 'required|string',
            'content' => 'sometimes|array',
            'parent_id' => 'sometimes|integer',
            'parent_type' => 'sometimes|string',
            'media' => 'sometimes|array',
            'sub_sections' => 'sometimes|array',
            'has_relation' => ['boolean', !$isUpdate ? 'required' : 'nullable'],
            'model_data' => 'required_if:has_relation,1|array',
            'model_data.*.model_id' => 'required_if:has_relation,1',
            'model_data.*.order' => 'required_if:has_relation,1',
            'model' => ['required_if:has_relation,1', 'nullable', Rule::in(SectionModelTypeEnum::getTablesNames())],
            'has_button' => ['boolean', !$isUpdate ? 'required' : 'nullable'],
            'button_data' => ['required_if:has_button,1'],
            'button_type' => ['required_if:has_button,1', 'nullable', new Enum(SectionButtonTypeEnum::class)],
            'type' => ['required', 'exists:section_types,slug'],
            'order' => ['integer', !$isUpdate ? 'required' : 'nullable'],
            'deleted_models' => 'nullable|array',
            'deleted_models.*.model_type' => ['nullable', Rule::in(SectionModelTypeEnum::getTablesNames())],
            'deleted_models.*.model_id' => ['nullable', 'integer', 'min:1'],
            'delete_all_models' => 'nullable|boolean',
            'media_deleted' => 'nullable|array',
        ];
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $rules["button_text.{$locale}"] = 'required_if:has_button,1';
        }

        $messages = [];
        $this->getUniqueOrderRule($rules, $messages, $data, $isUpdate);

        $sectionType = SectionType::where('slug', $data['type'] ?? '')->first();
        $this->applyTypeSpecificRules($rules, $sectionType, $data, $isUpdate);

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $this->formatAndThrowValidationException($validator, $parentFormId, $formId);
        }
        return $validator;
    }
    private function getUniqueOrderRule(array &$rules, &$messages, array $data, bool $isUpdate)
    {
        $id = $isUpdate ? (int) $data['id'] : 'NULL';

        if (!isset($rules['order'])) {
            $rules['order'] = [];
        }

        if (isset($data['order'])) {
            if (isset($data['parent_id'])) {
                $uniqueRule = "unique:cms_sections,order,$id,id,parent_id,{$data['parent_id']},parent_type,{$data['parent_type']}";
                $rules['order'][] = $uniqueRule;

                $messages['order.unique'] = $messages['order.unique'] ?? '';
                $messages['order.unique'] .= 'A section with the same order: ' . $data['order'] . ' already exists for the parent with name: ' . ($data['name'] ?? '') . ', parent id: ' . $data['parent_id'] . ' parent type: ' . $data['parent_type'];
            } elseif (isset($data['section_id'])) {
                $uniqueRule = "unique:cms_sections,order,$id,id,section_id,{$data['section_id']}";
                $rules['order'][] = $uniqueRule;

                $messages['order.unique'] = $messages['order.unique'] ?? '';
                $messages['order.unique'] .= 'A section with the same order: ' . $data['order'] . ' already exists for the parent section with name: ' . ($data['name'] ?? '') . '.';
            }
        }
    }
    private function applyTypeSpecificRules(array &$rules, ?SectionType $sectionType, $data, bool $isUpdate = false): void
    {
        if (!$sectionType || empty($sectionType->fields))
            return;

        foreach ($sectionType->fields as $field) {
            switch ($field) {
                case SectionFieldEnum::TITLE->value:
                    $rules['title'] = 'nullable|string|max:191';
                    break;
                case SectionFieldEnum::SUBTITLE->value:
                    $rules['subtitle'] = 'nullable|string|max:191';
                    break;
                case SectionFieldEnum::DESCRIPTION->value:
                    $rules['description'] = 'nullable|string|max:500';
                    break;
                case SectionFieldEnum::SHORT_DESCRIPTION->value:
                    $rules['short_description'] = 'nullable|string|max:255';
                    break;
                case SectionFieldEnum::ICON->value:
                    $this->addFileRule($rules, $data, 'icon', 'required|image|max:900', $isUpdate);
                    break;
                case SectionFieldEnum::IMAGE->value:
                    $this->addImageRules($rules, $data, ['images.*.file'], $isUpdate);
                    break;
                case SectionFieldEnum::GALLERY->value:
                    $rules['images'] = $isUpdate ? 'nullable|array' : 'required|array';
                    $this->addFileRule($rules, $data, 'images.*.file', 'required|image|max:900', $isUpdate);
                    break;
                case SectionFieldEnum::VIDEO->value:
                    $this->addVideoRules($rules, $data, ['videos.*.file'], ['images.*.file'], $isUpdate);
                    break;
                case SectionFieldEnum::BUTTONS->value:
                    $rules['content.buttons'] = 'nullable|array';
                    $rules['content.buttons.*.label'] = 'required|array';
                    $rules['content.buttons.*.url'] = 'required|string';
                    foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
                        $rules["content.buttons.*.label.{$locale}"] = 'required|string';
                    }
                    break;
            }
        }
    }
    private function addImageRules(array &$rules, $data, array $fields, bool $isUpdate = false): void
    {
        foreach ($fields as $field) {
            $this->addFileRule($rules, $data, $field, 'required|image|max:900', $isUpdate);
        }
    }
    private function addVideoRules(array &$rules, $data, array $videoFields, array $posterFields, bool $isUpdate = false): void
    {
        foreach ($videoFields as $field) {
            $this->addFileRule($rules, $data, $field, 'required|file|mimes:mp4,mov,avi,flv,webm|max:10240', $isUpdate);
        }

        foreach ($posterFields as $field) {
            $this->addFileRule($rules, $data, $field, 'required|image|max:900', $isUpdate);
        }
    }
    protected function addFileRule(array &$rules, array &$data, string $key, string $baseRule, bool $isUpdate = false)
    {
        if (isset($data[$key]) && $data[$key] instanceof UploadedFile) {
            $rules[$key] = $baseRule;
        } elseif (isset($data[$key]) && is_array($data[$key])) {
            foreach ($data[$key] as $index => $file) {
                if ($file instanceof UploadedFile) {
                    $rules["{$key}.{$index}"] = $baseRule;
                } else
                    $rules["{$key}.{$index}"] = 'required';
            }
        } else
            $rules[$key] = $isUpdate ? 'nullable' : 'required';
    }
    private function validateType($data)
    {
        $parentFormId = $data['parentFormId'] ?? null;
        $formId = $data['formId'] ?? null;

        $validator = Validator::make($data, [
            'type' => 'required|exists:section_types,slug',
        ]);

        if ($validator->fails())
            $this->formatAndThrowValidationException($validator, $parentFormId, $formId);

        return $validator;
    }
    private function formatAndThrowValidationException($validator, ?string $parentFormId, ?string $formId): void
    {
        $errors = $validator->errors()->toArray();
        $formattedErrors = [];

        foreach ($errors as $key => $messages) {
            if ($formId && $parentFormId) {
                $formattedErrors["{$parentFormId}**{$formId}*$key"] = $messages;
            } else {
                $formattedErrors[$key] = $messages;
            }
        }

        throw new \Illuminate\Validation\ValidationException(
            $validator,
            response()->json(['errors' => $formattedErrors], 422)
        );
    }
    public function getSectionTypes()
    {
        return SectionType::all();
    }
    private function removeAllMediaForSection($section)
    {
        $this->mediaService->removeImages($section, null, true);
        $this->mediaService->removeFiles($section, null, true);
        $this->mediaService->removeVideos($section, null, true);
        $this->mediaService->removeIcons($section, null, true);
    }
}
