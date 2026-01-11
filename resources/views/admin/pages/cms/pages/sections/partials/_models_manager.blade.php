@php
    // Get section type to check if it supports models
    $sectionType = null;
    $sectionTypeSlug = null;
    $sectionTypeFields = [];

    if ($section) {
        if ($section->sectionTypes && method_exists($section->sectionTypes, 'count') && $section->sectionTypes->count() > 0) {
            $sectionType = $section->sectionTypes->first();
            if ($sectionType) {
                $sectionTypeSlug = $sectionType->slug ?? null;
                $sectionTypeFields = $sectionType->fields ?? [];
            }
        } elseif ($section->type) {
            // Fallback to direct type field
            $sectionTypeSlug = $section->type;
        }
    }

    // For subsections: if type is missing, inherit from parent section
    if ($isSubsection && empty($sectionTypeFields) && isset($parentSection) && $parentSection) {
        if ($parentSection->sectionTypes && method_exists($parentSection->sectionTypes, 'count') && $parentSection->sectionTypes()->count() > 0) {
            $parentSectionType = $parentSection->sectionTypes()->first();
            if ($parentSectionType) {
                $sectionTypeSlug = $parentSectionType->slug ?? null;
                $sectionTypeFields = $parentSectionType->fields ?? [];
            }
        } elseif ($parentSection->type) {
            $sectionTypeSlug = $parentSection->type;
        }
    }

    // Check if this section type supports models (has 'model' in fields array)
    $supportsModels = isset($sectionTypeFields) && is_array($sectionTypeFields) && in_array('model', $sectionTypeFields);

    // Get existing models for this section if available
    $existingModels = [];
    $modelType = 'pages'; // Default model type
    if ($section && $section->models && method_exists($section->models, 'each')) {
        foreach ($section->models as $sectionModel) {
            if ($sectionModel && $sectionModel->model) {
                $modelClass = get_class($sectionModel->model);
                // Determine model type table name
                if (str_contains($modelClass, 'Service')) {
                    $modelType = 'services';
                } elseif (str_contains($modelClass, 'Project')) {
                    $modelType = 'projects';
                } elseif (str_contains($modelClass, 'Tag')) {
                    $modelType = 'tags';
                } elseif (str_contains($modelClass, 'Page')) {
                    $modelType = 'pages';
                } elseif (str_contains($modelClass, 'Partner')) {
                    $modelType = 'partners';
                }

                // Extract model name properly (handle translatable fields)
                $modelName = $sectionModel->model->name ?? $sectionModel->model->title ?? null;
                if (is_array($modelName)) {
                    // Translatable field - get current locale or fallback
                    $currentLocale = app()->getLocale();
                    $fallbackLocale = $currentLocale === 'ar' ? 'en' : 'ar';
                    $modelName = $modelName[$currentLocale] ?? $modelName[$fallbackLocale] ?? reset($modelName) ?? 'N/A';
                } elseif (!$modelName) {
                    $modelName = $sectionModel->model->slug ?? 'N/A';
                }

                $existingModels[] = [
                    'id' => $sectionModel->model->id,
                    'name' => $modelName,
                    'type' => class_basename($sectionModel->model), // Class name for display
                    'order' => $sectionModel->order ?? 0,
                    'model_type' => $modelType, // Table name for backend
                    'modelTypeTable' => $modelType, // Table name for JavaScript
                    'slug' => $sectionModel->model->slug ?? null,
                ];
            }
        }
        // Sort by order
        usort($existingModels, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
    }

    $inputPrefix = $isSubsection
        ? "sections[{$sectionIndex}][sub_sections][{$subIndex}]"
        : "sections[{$sectionIndex}]";

    $uniqueId = $sectionIndex . ($isSubsection ? '-sub-' . $subIndex : '');
@endphp

@if(!empty($supportsModels))

    {{-- Models Manager Block --}}
    <div class="card mt-3 mb-3 models-manager-block" id="models-manager-{{ $uniqueId }}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="mdi mdi-view-list me-1"></i>
                {{ __('custom.words.models') }}
                <span class="badge bg-primary ms-2" id="models-count-{{ $uniqueId }}">{{ count($existingModels) }}</span>
            </h6>
            <div>
                <button type="button" class="btn btn-sm btn-primary add-models-btn" data-section-index="{{ $sectionIndex }}"
                    data-is-subsection="{{ $isSubsection ? '1' : '0' }}" data-sub-index="{{ $subIndex ?? '' }}">
                    <i class="mdi mdi-plus me-1"></i>
                    {{ __('custom.words.add_models') }}
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger clear-models-btn"
                    data-section-index="{{ $sectionIndex }}" data-is-subsection="{{ $isSubsection ? '1' : '0' }}"
                    data-sub-index="{{ $subIndex ?? '' }}">
                    <i class="mdi mdi-delete-outline me-1"></i>
                    {{ __('custom.words.clear_all') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            {{-- Hidden inputs for form submission --}}
            <input type="hidden" name="{{ $inputPrefix }}[has_relation]"
                value="{{ count($existingModels) > 0 ? '1' : '0' }}" id="has_relation-{{ $uniqueId }}">
            <input type="hidden" name="{{ $inputPrefix }}[model]" value="{{ $modelType }}" id="model-type-{{ $uniqueId }}">
            <input type="hidden" name="{{ $inputPrefix }}[type]" value="{{ $sectionTypeSlug }}"
                id="section-type-{{ $uniqueId }}" data-section-type="{{ $sectionTypeSlug }}">

            {{-- Selected Models List (Sortable) --}}
            <div class="selected-models-list" id="selected-models-{{ $uniqueId }}" data-section-index="{{ $sectionIndex }}"
                data-is-subsection="{{ $isSubsection ? '1' : '0' }}" data-sub-index="{{ $subIndex ?? '' }}"
                data-unique-id="{{ $uniqueId }}">
                @if(count($existingModels) > 0)
                    @foreach($existingModels as $index => $model)
                        @php
                            // Get model media for preview
                            $modelInstance = null;
                            $modelMedia = null;

                            if ($section && $section->models) {
                                $modelInstance = $section->models->where('model_id', $model['id'])->first();
                            }

                            if ($modelInstance && $modelInstance->model) {
                                // Safely access relationships with null checks
                                $modelObj = $modelInstance->model;

                                // Check for single media first
                                if ($modelObj->image) {
                                    $modelMedia = $modelObj->image;
                                } elseif ($modelObj->images && method_exists($modelObj->images, 'first')) {
                                    $modelMedia = $modelObj->images->first();
                                } elseif ($modelObj->video) {
                                    $modelMedia = $modelObj->video;
                                } elseif ($modelObj->videos && method_exists($modelObj->videos, 'first')) {
                                    $modelMedia = $modelObj->videos->first();
                                } elseif ($modelObj->icon) {
                                    $modelMedia = $modelObj->icon;
                                } elseif ($modelObj->icons && method_exists($modelObj->icons, 'first')) {
                                    $modelMedia = $modelObj->icons->first();
                                } elseif ($modelObj->file) {
                                    $modelMedia = $modelObj->file;
                                } elseif ($modelObj->files && method_exists($modelObj->files, 'first')) {
                                    $modelMedia = $modelObj->files->first();
                                }
                            }
                        @endphp
                        <div class="selected-model-item mb-2 p-2 border rounded d-flex align-items-center justify-content-between"
                            data-model-id="{{ $model['id'] }}" data-model-type="{{ $model['type'] }}"
                            data-model-type-table="{{ $model['model_type'] ?? 'pages' }}">
                            <div class="d-flex align-items-center flex-grow-1">
                                <i class="mdi mdi-drag-vertical me-2 text-muted" style="cursor: move;"
                                    title="{{ __('custom.words.drag_to_reorder') ?? 'Drag to reorder' }}"></i>
                                @if($modelMedia)
                                    @php
                                        $mediaUrl = $modelMedia->url ?? url('storage/' . $modelMedia->media_path . '/' . $modelMedia->name);
                                        $mediaType = $modelMedia->type ?? 'image';
                                        $mediaIcon = $mediaType === 'video' ? 'video' : ($mediaType === 'file' ? 'file' : ($mediaType === 'icon' ? 'star' : 'image'));
                                        $modelNameForAlt = is_array($model['name']) ? ($model['name'][app()->getLocale()] ?? reset($model['name']) ?? '') : ($model['name'] ?? '');
                                    @endphp
                                    <div class="model-media-preview me-3 position-relative"
                                        style="width: 60px; height: 60px; flex-shrink: 0;">
                                        @if($mediaType === 'image' || $mediaType === 'icon')
                                            <img src="{{ $mediaUrl }}" alt="{{ $modelNameForAlt }}" class="img-thumbnail rounded"
                                                style="width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: transform 0.2s;"
                                                onclick="if(window.showMediaPreview) window.showMediaPreview('{{ $mediaUrl }}', '{{ $mediaType }}', '{{ addslashes($modelNameForAlt) }}')"
                                                onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                loading="lazy">
                                            <div class="d-none align-items-center justify-content-center bg-light rounded position-absolute top-0 start-0"
                                                style="width: 100%; height: 100%;">
                                                <i class="mdi mdi-{{ $mediaIcon }} mdi-24px text-muted"></i>
                                            </div>
                                        @elseif($mediaType === 'video')
                                            @php
                                                $posterUrl = null;
                                                if ($modelMedia && isset($modelMedia->poster) && $modelMedia->poster) {
                                                    $posterUrl = $modelMedia->poster->url ?? null;
                                                }
                                                $videoThumbnailUrl = $posterUrl ?? $mediaUrl;
                                            @endphp
                                            <img src="{{ $videoThumbnailUrl }}" alt="{{ $modelNameForAlt }}" class="img-thumbnail rounded"
                                                style="width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: transform 0.2s;"
                                                onclick="if(window.showMediaPreview) window.showMediaPreview('{{ $mediaUrl }}', 'video', '{{ addslashes($modelNameForAlt) }}')"
                                                onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                loading="lazy">
                                            <div class="d-none align-items-center justify-content-center bg-light rounded position-absolute top-0 start-0"
                                                style="width: 100%; height: 100%;">
                                                <i class="mdi mdi-video mdi-24px text-muted"></i>
                                            </div>
                                            <div class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 rounded-circle p-1">
                                                <i class="mdi mdi-play mdi-12px text-white"></i>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light rounded h-100"
                                                style="cursor: pointer;"
                                                onclick="if(window.showMediaPreview) window.showMediaPreview('{{ $mediaUrl }}', 'file', '{{ addslashes($modelNameForAlt) }}')"
                                                title="{{ __('custom.words.click_to_preview') ?? 'Click to preview' }}">
                                                <i class="mdi mdi-file mdi-24px text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="model-media-preview me-3 d-flex align-items-center justify-content-center bg-light rounded"
                                        style="width: 60px; height: 60px; flex-shrink: 0;"
                                        title="{{ __('custom.words.no_media') ?? 'No media available' }}">
                                        <i class="mdi mdi-image mdi-24px text-muted"></i>
                                    </div>
                                @endif
                                <div class="order-badge me-2 d-flex align-items-center justify-content-center bg-primary text-white rounded"
                                    style="width: 30px; height: 30px; font-weight: bold; font-size: 0.875rem; flex-shrink: 0;">
                                    {{ $model['order'] ?? ($index + 1) }}
                                </div>
                                <div class="flex-grow-1">
                                    @php
                                        // Extract model name properly (handle translatable fields)
                                        $modelName = $model['name'] ?? '';
                                        if (is_array($modelName)) {
                                            $currentLocale = app()->getLocale();
                                            $fallbackLocale = $currentLocale === 'ar' ? 'en' : 'ar';
                                            $modelName = $modelName[$currentLocale] ?? $modelName[$fallbackLocale] ?? (is_array($modelName) ? reset($modelName) : '') ?? '';
                                        }
                                        $modelName = $modelName ?: ($model['slug'] ?? 'Model #' . ($model['id'] ?? ''));
                                    @endphp
                                    <strong>{{ $modelName }}</strong>
                                    <small class="text-muted ms-2">({{ $model['type'] ?? 'Model' }})</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-model-btn"
                                data-model-id="{{ $model['id'] }}" title="{{ __('custom.words.remove') }}">
                                <i class="mdi mdi-close"></i>
                            </button>
                            {{-- Hidden inputs for model_data --}}
                            <input type="hidden" name="{{ $inputPrefix }}[model_data][{{ $index }}][model_id]"
                                value="{{ $model['id'] }}" data-model-id="{{ $model['id'] }}" class="model-id-input">
                            <input type="hidden" name="{{ $inputPrefix }}[model_data][{{ $index }}][order]"
                                value="{{ $model['order'] ?? ($index + 1) }}" data-model-id="{{ $model['id'] }}"
                                class="order-hidden-input">
                        </div>
                    @endforeach
                @else
                    <div class="empty-state text-center text-muted py-4">
                        <i class="mdi mdi-information-outline mdi-48px mb-2"></i>
                        <p class="mb-0">{{ __('custom.words.no_models_selected') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Models Selection Modal --}}
    <div class="modal fade" id="modelsModal-{{ $uniqueId }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('custom.words.select') }} {{ __('custom.words.models') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Model Type Selector --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('custom.words.model_type') }}</label>
                        <select class="form-select model-type-selector" id="modelTypeSelect-{{ $uniqueId }}">
                            <option value="pages">{{ __('custom.words.pages') }}</option>
                            <option value="services">{{ __('custom.words.services') }}</option>
                            <option value="projects">{{ __('custom.words.projects') }}</option>
                            <option value="tags">{{ __('custom.words.tags') }}</option>
                            <option value="partners">{{ __('custom.words.partners') }}</option>
                        </select>
                    </div>

                    {{-- Search --}}
                    <div class="mb-3">
                        <input type="text" class="form-control models-search" placeholder="{{ __('custom.words.search') }}"
                            id="modelsSearch-{{ $uniqueId }}">
                    </div>

                    {{-- Available Models List --}}
                    <div class="available-models-list" id="availableModels-{{ $uniqueId }}"
                        style="max-height: 400px; overflow-y: auto;">
                        <div class="text-center text-muted py-4">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('custom.words.cancel') }}</button>
                    <button type="button" class="btn btn-primary confirm-selection-btn"
                        data-section-index="{{ $sectionIndex }}" data-is-subsection="{{ $isSubsection ? '1' : '0' }}"
                        data-sub-index="{{ $subIndex ?? '' }}">
                        {{ __('custom.words.add_selected') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- Section type does not support models --}}
    <div style="display: none;"></div>
@endif
