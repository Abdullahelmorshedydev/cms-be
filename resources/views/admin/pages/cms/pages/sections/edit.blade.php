@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.words.sections') . ' - ' . $page->name)

@php
    // Get supported locales
    $locales = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLanguagesKeys();
@endphp

@section('css')
<style>
    /* CKEditor Transparent Theme */
    .ck-editor__editable {
        background: transparent !important;
        color: inherit !important;
    }

    .ck-editor__editable.ck-focused {
        background: transparent !important;
    }

    .ck.ck-editor {
        background: transparent !important;
    }

    /* Toolbar - match text color */
    .ck.ck-toolbar {
        background: transparent !important;
        border: 1px solid var(--bs-border-color, #d9dee3) !important;
        border-bottom: none !important;
        border-radius: 0.375rem 0.375rem 0 0 !important;
    }

    .ck.ck-toolbar .ck-button {
        color: var(--bs-body-color, #697a8d) !important;
    }

    .ck.ck-toolbar .ck-button:hover:not(.ck-disabled) {
        background: rgba(0, 0, 0, 0.05) !important;
        color: var(--bs-body-color, #697a8d) !important;
    }

    .ck.ck-toolbar .ck-button.ck-on {
        background: rgba(0, 0, 0, 0.1) !important;
        color: var(--bs-body-color, #697a8d) !important;
    }

    .ck.ck-toolbar .ck-button .ck-icon {
        color: var(--bs-body-color, #697a8d) !important;
    }

    .ck.ck-toolbar .ck-button:hover .ck-icon {
        color: var(--bs-body-color, #697a8d) !important;
    }

    .ck.ck-toolbar .ck-button.ck-on .ck-icon {
        color: var(--bs-body-color, #697a8d) !important;
    }

    /* Dropdown panels */
    .ck.ck-dropdown__panel {
        background: var(--bs-body-bg) !important;
        border-color: var(--bs-border-color, #d9dee3) !important;
    }

    .ck.ck-list__item {
        color: var(--bs-body-color, #697a8d) !important;
    }

    .ck.ck-list__item:hover {
        background: rgba(0, 0, 0, 0.05) !important;
    }

    /* Editor content area - match form-control border */
    .ck.ck-editor__main > .ck-editor__editable {
        background: transparent !important;
        border: 1px solid var(--bs-border-color, #d9dee3) !important;
        border-radius: 0 0 0.375rem 0.375rem !important;
        color: var(--bs-body-color, #697a8d) !important;
        min-height: 120px !important;
    }

    .ck.ck-editor__main > .ck-editor__editable:focus {
        border-color: var(--bs-border-color, #d9dee3) !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
        outline: 0 !important;
    }

    /* Match form-control styling */
    .section-description-editor + .ck.ck-editor {
        border: 1px solid var(--bs-border-color, #d9dee3) !important;
        border-radius: 0.375rem !important;
    }

    /* Ensure text color matches */
    .ck.ck-editor__editable p,
    .ck.ck-editor__editable li,
    .ck.ck-editor__editable {
        color: var(--bs-body-color, #697a8d) !important;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ __('custom.words.edit') }} {{ __('custom.words.sections') }} - {{ $page->name }}</h4>
            <a href="{{ route('dashboard.cms.pages.index') }}" class="btn btn-secondary">
                {{ __('custom.words.back') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.cms.sections.group.update') }}" method="POST" enctype="multipart/form-data" id="sectionsForm">
                @csrf

                {{-- Hidden fields for updateGroup endpoint --}}
                <input type="hidden" name="parent_id" value="{{ $page->id }}">
                <input type="hidden" name="model_type" value="{{ \App\Models\Page::class }}">

                {{-- Sections Accordion --}}
                <div class="accordion" id="sectionsAccordion">
                    @forelse($sections as $sectionIndex => $section)
                    @php
                        $sectionContent = $section && $section->content ? (is_string($section->content) ? json_decode($section->content, true) : $section->content) : [];
                            $subSections = $section->sections ?? collect([]);
                            $canAddSubSection = $subSections->count() >= 1;
                            $sectionName = $section->name ? ucfirst(str_replace('_', ' ', $section->name)) : 'Section ' . ($sectionIndex + 1);
                    @endphp

                        <div class="accordion-item mb-3 section-card" data-section-id="{{ $section->id }}">
                            <h2 class="accordion-header" id="section-heading-{{ $sectionIndex }}">
                                <button class="accordion-button {{ $sectionIndex === 0 ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#section-collapse-{{ $sectionIndex }}"
                                    aria-expanded="{{ $sectionIndex === 0 ? 'true' : 'false' }}"
                                    aria-controls="section-collapse-{{ $sectionIndex }}">
                                    <strong>{{ $sectionName }}</strong>
                                    <small class="text-muted ms-2">{{ $section->type }}</small>
                                </button>
                            </h2>
                            <div id="section-collapse-{{ $sectionIndex }}"
                                class="accordion-collapse collapse {{ $sectionIndex === 0 ? 'show' : '' }}"
                                aria-labelledby="section-heading-{{ $sectionIndex }}"
                                data-bs-parent="#sectionsAccordion">
                                <div class="accordion-body">
                            {{-- Hidden fields for section metadata --}}
                                    <input type="hidden" name="sections[{{ $sectionIndex }}][id]" value="{{ $section->id }}">
                                    <input type="hidden" name="sections[{{ $sectionIndex }}][name]" value="{{ $section->name }}">
                                    <input type="hidden" name="sections[{{ $sectionIndex }}][type]" value="{{ $section->sectionTypes()->first()->slug }}">
                                    <input type="hidden" name="sections[{{ $sectionIndex }}][order]" value="{{ $section->order }}">
                            <input type="hidden" name="sections[{{ $sectionIndex }}][has_button]" value="0">
                            <input type="hidden" name="sections[{{ $sectionIndex }}][has_relation]" value="0">

                                    {{-- Render section editor based on type --}}
                                    @include('admin.pages.cms.pages.sections.partials._section_editor', [
                                        'section' => $section,
                                        'sectionIndex' => $sectionIndex,
                                        'sectionContent' => $sectionContent,
                                        'locales' => $locales,
                                    ])

                                    {{-- Subsections Accordion --}}
                                    @if($subSections->count() > 0)
                                        <div class="mt-4">
                                            <h6 class="mb-3">{{ __('custom.words.subsections') }}</h6>
                                            <div class="accordion" id="subsectionsAccordion-{{ $sectionIndex }}">
                                                @foreach($subSections as $subIndex => $subSection)
                                                    @php
                                                        $subSectionContent = $subSection && $subSection->content ? (is_string($subSection->content) ? json_decode($subSection->content, true) : $subSection->content) : [];
                                    @endphp
                                                    <div class="accordion-item mb-2 subsection-card" data-subsection-id="{{ $subSection->id }}">
                                                        <h2 class="accordion-header" id="subsection-heading-{{ $sectionIndex }}-{{ $subIndex }}">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#subsection-collapse-{{ $sectionIndex }}-{{ $subIndex }}"
                                                                aria-expanded="false"
                                                                aria-controls="subsection-collapse-{{ $sectionIndex }}-{{ $subIndex }}">
                                                                <strong>{{ __('custom.words.subsection') }} {{ $subIndex + 1 }}</strong>
                                                                <small class="text-muted ms-2">{{ $subSection->sectionTypes()->first()->slug }}</small>
                                                            </button>
                                                        </h2>
                                                        <div id="subsection-collapse-{{ $sectionIndex }}-{{ $subIndex }}"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="subsection-heading-{{ $sectionIndex }}-{{ $subIndex }}"
                                                            data-bs-parent="#subsectionsAccordion-{{ $sectionIndex }}">
                                                            <div class="accordion-body">
                                                                <input type="hidden" name="sections[{{ $sectionIndex }}][sub_sections][{{ $subIndex }}][id]" value="{{ $subSection->id }}">
                                                                <input type="hidden" name="sections[{{ $sectionIndex }}][sub_sections][{{ $subIndex }}][type]" value="{{ $subSection->sectionTypes()->first()->slug }}">
                                                                <input type="hidden" name="sections[{{ $sectionIndex }}][sub_sections][{{ $subIndex }}][order]" value="{{ $subSection->order ?? $subIndex++ }}">

                                                                {{-- Render subsection editor based on type --}}
                                                                @include('admin.pages.cms.pages.sections.partials._section_editor', [
                                                                    'section' => $subSection,
                                                                    'sectionIndex' => $sectionIndex,
                                                                    'sectionContent' => $subSectionContent,
                                                                    'locales' => $locales,
                                                                    'isSubsection' => true,
                                                                    'subIndex' => $subIndex,
                                                                ])
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                </div>
                            @endif

                                    {{-- Add Subsection Button --}}
                                    <div class="mt-3">
                                        @if($canAddSubSection)
                                            <button type="button" class="btn btn-sm btn-outline-primary add-subsection-btn"
                                                data-section-index="{{ $sectionIndex }}"
                                                data-section-type="{{ $section->type }}">
                                                <i class="mdi mdi-plus me-1"></i>
                                                {{ __('custom.words.add') }} {{ __('custom.words.subsection') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                            </div>
                                        </div>
                    @empty
                        <div class="alert alert-info">
                            {{ __('custom.messages.no_sections_found') }}
                        </div>
                    @endforelse
                    </div>

                {{-- Submit Button --}}
                <div class="card-footer mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save me-1"></i>
                        {{ __('custom.words.save') }} {{ __('custom.words.all') }} {{ __('custom.words.sections') }}
                    </button>
                    <a href="{{ route('dashboard.cms.pages.index') }}" class="btn btn-secondary">
                        {{ __('custom.words.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Template for new subsection (hidden) --}}
<div id="subsection-template" style="display: none;">
    <div class="accordion-item mb-2 subsection-card" data-temp-subsection="true">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button"
                data-bs-toggle="collapse"
                data-bs-target=""
                aria-expanded="false">
                <strong>{{ __('custom.words.subsection') }} <span class="subsection-number"></span></strong>
                <small class="text-muted ms-2 subsection-type-display"></small>
            </button>
        </h2>
        <div class="accordion-collapse collapse">
            <div class="accordion-body">
                <input type="hidden" class="subsection-type-input" name="" value="">
                <input type="hidden" class="subsection-type-slug-input" name="" value="">
                <input type="hidden" class="subsection-order-input" name="" value="">

                <div class="subsection-content-editor">
                    {{-- Content will be dynamically inserted based on type --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Initialize CKEditor for description fields
    function initializeCKEditors() {
        document.querySelectorAll('.section-description-editor').forEach(textarea => {
            // Skip if already initialized
            if (textarea.dataset.ckeditorInitialized === 'true') {
                return;
            }

            if (typeof ClassicEditor !== 'undefined') {
                ClassicEditor
                    .create(textarea, {
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'link', '|',
                                'bulletedList', 'numberedList', '|',
                                'blockQuote', 'insertTable', '|',
                                'undo', 'redo'
                            ]
                        },
                        language: 'en',
                        licenseKey: '',
                    })
                    .then(editor => {
                        textarea.dataset.ckeditorInitialized = 'true';
                        if (textarea.id) {
                            window[textarea.id + '_editor'] = editor;
                        }
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor:', error);
                    });
            } else {
                console.warn('CKEditor (ClassicEditor) is not loaded. Make sure the script is included.');
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit for accordion animations to complete
        setTimeout(initializeCKEditors, 300);
    });

    // Re-initialize when accordions are expanded (for lazy loading)
    document.addEventListener('shown.bs.collapse', function(e) {
        setTimeout(initializeCKEditors, 100);
    });

    // Add Subsection functionality
    document.querySelectorAll('.add-subsection-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const sectionIndex = this.dataset.sectionIndex;
            const sectionType = this.dataset.sectionType;
            const sectionCard = this.closest('.section-card');
            const subsectionsContainer = sectionCard.querySelector('.mt-4') || createSubsectionsContainer(sectionCard, sectionIndex);
            const subsectionsAccordion = subsectionsContainer.querySelector('.accordion') || createSubsectionsAccordion(subsectionsContainer);

            // Get existing subsections count
            const existingSubsections = subsectionsAccordion.querySelectorAll('.subsection-card:not([data-temp-subsection="true"])');
            const newSubIndex = existingSubsections.length;

            // Clone template
            const template = document.getElementById('subsection-template');
            const newSubsection = template.cloneNode(true);
            newSubsection.id = '';
            newSubsection.style.display = 'block';

            // Generate unique IDs
            const collapseId = `subsection-collapse-${sectionIndex}-${newSubIndex}`;
            const headingId = `subsection-heading-${sectionIndex}-${newSubIndex}`;
            const targetId = `#${collapseId}`;

            // Update accordion attributes
            const accordionHeader = newSubsection.querySelector('.accordion-header');
            accordionHeader.id = headingId;

            const accordionButton = newSubsection.querySelector('.accordion-button');
            accordionButton.setAttribute('data-bs-target', targetId);
            accordionButton.setAttribute('aria-controls', collapseId);

            const accordionCollapse = newSubsection.querySelector('.accordion-collapse');
            accordionCollapse.id = collapseId;
            accordionCollapse.setAttribute('aria-labelledby', headingId);
            accordionCollapse.setAttribute('data-bs-parent', `#subsectionsAccordion-${sectionIndex}`);

            // Update inputs
            const typeInput = newSubsection.querySelector('.subsection-type-input');
            const typeSlugInput = newSubsection.querySelector('.subsection-type-slug-input');
            const orderInput = newSubsection.querySelector('.subsection-order-input');
            const typeDisplayText = newSubsection.querySelector('.subsection-type-display');
            const subsectionNumber = newSubsection.querySelector('.subsection-number');

            typeInput.name = `sections[${sectionIndex}][sub_sections][${newSubIndex}][type]`;
            typeInput.value = sectionType;
            if (typeSlugInput) {
                typeSlugInput.name = `sections[${sectionIndex}][sub_sections][${newSubIndex}][type_slug]`;
                typeSlugInput.value = sectionType;
            }
            orderInput.name = `sections[${sectionIndex}][sub_sections][${newSubIndex}][order]`;
            orderInput.value = newSubIndex;
            typeDisplayText.textContent = sectionType;
            subsectionNumber.textContent = newSubIndex + 1;

            // Render content editor based on type
            const contentEditor = newSubsection.querySelector('.subsection-content-editor');
            contentEditor.innerHTML = getSectionEditorHTML(sectionType, sectionIndex, newSubIndex, true);

            // Insert into accordion
            subsectionsAccordion.appendChild(newSubsection);

            // Update subsection numbers
            updateSubsectionNumbers(subsectionsAccordion);

            // Initialize CKEditor for new subsection description fields
            setTimeout(() => {
                initializeCKEditors();
            }, 200);

            // Enable add button if it was disabled
            if (!this.closest('.section-card').querySelector('.add-subsection-btn').disabled) {
                // Button is now enabled since we have at least one subsection
            }
        });
    });

    function createSubsectionsContainer(sectionCard, sectionIndex) {
        const container = document.createElement('div');
        container.className = 'mt-4';
        const header = document.createElement('h6');
        header.className = 'mb-3';
        header.textContent = '{{ __('custom.words.subsections') }}';
        container.appendChild(header);

        const accordionBody = sectionCard.querySelector('.accordion-body');
        const addButtonContainer = accordionBody.querySelector('.mt-3');
        accordionBody.insertBefore(container, addButtonContainer);

        return container;
    }

    function createSubsectionsAccordion(container) {
        const accordion = document.createElement('div');
        accordion.className = 'accordion';
        const sectionCard = container.closest('.section-card');
        const sectionIndex = sectionCard ? Array.from(document.querySelectorAll('.section-card')).indexOf(sectionCard) : 0;
        accordion.id = `subsectionsAccordion-${sectionIndex}`;
        container.appendChild(accordion);
        return accordion;
    }

    function updateSubsectionNumbers(container) {
        const subsections = container.querySelectorAll('.subsection-card');
        subsections.forEach((subsection, index) => {
            const numberSpan = subsection.querySelector('.subsection-number');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    function getSectionEditorHTML(type, sectionIndex, subIndex, isSubsection) {
        const prefix = isSubsection
            ? `sections[${sectionIndex}][sub_sections][${subIndex}][content]`
            : `sections[${sectionIndex}][content]`;

        // This will be handled by the partial
        // For now, return a placeholder that will be replaced
        return `<div class="section-editor-placeholder" data-type="${type}" data-prefix="${prefix}"></div>`;
    }

    // Image preview functionality
    document.querySelectorAll('input[type="file"][accept="image/*"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = input.parentElement.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'image-preview mt-2';
                        preview.style.maxWidth = '200px';
                        preview.style.maxHeight = '100px';
                        input.parentElement.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
