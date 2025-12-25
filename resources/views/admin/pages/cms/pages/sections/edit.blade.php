@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.words.sections') . ' - ' . $page->name)

@php
    // Get supported locales
    $locales = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLanguagesKeys();
    
    // STATIC section keys and types - DO NOT CHANGE
    $staticSections = [
        'hero' => ['type' => 'hero', 'order' => 1],
        'about' => ['type' => 'rich_text', 'order' => 2],
        'services' => ['type' => 'cards_grid', 'order' => 3],
        'cta' => ['type' => 'cta_banner', 'order' => 4],
        'faq' => ['type' => 'faq_accordion', 'order' => 5],
    ];
    
    // Helper function to get section content value
    function getSectionContent($section, $key, $locale = null) {
        if (!$section || !$section->content) {
            return '';
        }
        $content = is_string($section->content) ? json_decode($section->content, true) : $section->content;
        if ($locale) {
            return $content[$key][$locale] ?? '';
        }
        return $content[$key] ?? '';
    }
@endphp

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

                {{-- Render each static section in order --}}
                @foreach($staticSections as $sectionKey => $sectionConfig)
                    @php
                        $section = $sectionsByKey[$sectionKey] ?? null;
                        $sectionIndex = $loop->index;
                        $sectionId = $section ? $section->id : null;
                        $sectionContent = $section && $section->content ? (is_string($section->content) ? json_decode($section->content, true) : $section->content) : [];
                    @endphp

                    <div class="card mb-4 section-card" data-section-key="{{ $sectionKey }}">
                        <div class="card-header">
                            <h5 class="mb-0">
                                {{ ucfirst(str_replace('_', ' ', $sectionKey)) }} Section
                                <small class="text-muted">({{ $sectionConfig['type'] }})</small>
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Hidden fields for section metadata --}}
                            @if($sectionId)
                                <input type="hidden" name="sections[{{ $sectionIndex }}][id]" value="{{ $sectionId }}">
                            @endif
                            <input type="hidden" name="sections[{{ $sectionIndex }}][name]" value="{{ $sectionKey }}">
                            <input type="hidden" name="sections[{{ $sectionIndex }}][type]" value="{{ $sectionConfig['type'] }}">
                            <input type="hidden" name="sections[{{ $sectionIndex }}][order]" value="{{ $sectionConfig['order'] }}">
                            <input type="hidden" name="sections[{{ $sectionIndex }}][has_button]" value="0">
                            <input type="hidden" name="sections[{{ $sectionIndex }}][has_relation]" value="0">

                            {{-- Hero Section Fields --}}
                            @if($sectionConfig['type'] === 'hero')
                                <div class="row">
                                    {{-- Title --}}
                                    @foreach($locales as $locale)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" 
                                                    class="form-control @error("sections.$sectionIndex.content.title.$locale") is-invalid @enderror"
                                                    name="sections[{{ $sectionIndex }}][content][title][{{ $locale }}]"
                                                    value="{{ old("sections.$sectionIndex.content.title.$locale", $sectionContent['title'][$locale] ?? '') }}"
                                                    placeholder="{{ __('custom.inputs.title_' . $locale) }}">
                                                <label>{{ __('custom.inputs.title_' . $locale) }}</label>
                                                @error("sections.$sectionIndex.content.title.$locale")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Subtitle --}}
                                    @foreach($locales as $locale)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" 
                                                    class="form-control @error("sections.$sectionIndex.content.subtitle.$locale") is-invalid @enderror"
                                                    name="sections[{{ $sectionIndex }}][content][subtitle][{{ $locale }}]"
                                                    value="{{ old("sections.$sectionIndex.content.subtitle.$locale", $sectionContent['subtitle'][$locale] ?? '') }}"
                                                    placeholder="{{ __('custom.inputs.subtitle_' . $locale) }}">
                                                <label>{{ __('custom.inputs.subtitle_' . $locale) }}</label>
                                                @error("sections.$sectionIndex.content.subtitle.$locale")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Background Image --}}
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">{{ __('custom.inputs.background_image') }}</label>
                                        <input type="file" 
                                            class="form-control @error("sections.$sectionIndex.images") is-invalid @enderror"
                                            name="sections[{{ $sectionIndex }}][images][0][file]"
                                            accept="image/*">
                                        @if($section && $section->getFirstMedia('hero'))
                                            <div class="mt-2">
                                                <small class="text-muted">Current: </small>
                                                <img src="{{ $section->getFirstMedia('hero')->getUrl() }}" alt="Current" style="max-width: 200px; max-height: 100px;">
                                            </div>
                                        @endif
                                        @error("sections.$sectionIndex.images")
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Button Fields --}}
                                    <input type="hidden" name="sections[{{ $sectionIndex }}][has_button]" value="1">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" 
                                                class="form-control"
                                                name="sections[{{ $sectionIndex }}][button_data]"
                                                value="{{ old("sections.$sectionIndex.button_data", $section->button_data ?? '/contact') }}"
                                                placeholder="Button Link">
                                            <label>{{ __('custom.inputs.button_link') }}</label>
                                        </div>
                                    </div>
                                    @foreach($locales as $locale)
                                        <div class="col-md-3 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" 
                                                    class="form-control"
                                                    name="sections[{{ $sectionIndex }}][button_text][{{ $locale }}]"
                                                    value="{{ old("sections.$sectionIndex.button_text.$locale", $section && method_exists($section, 'getTranslation') ? $section->getTranslation('button_text', $locale) : ($section->button_text[$locale] ?? '')) }}"
                                                    placeholder="{{ __('custom.inputs.button_text_' . $locale) }}">
                                                <label>{{ __('custom.inputs.button_text_' . $locale) }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Rich Text Section Fields --}}
                            @if($sectionConfig['type'] === 'rich_text')
                                <div class="row">
                                    @foreach($locales as $locale)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <textarea 
                                                    class="form-control @error("sections.$sectionIndex.content.description.$locale") is-invalid @enderror"
                                                    name="sections[{{ $sectionIndex }}][content][description][{{ $locale }}]"
                                                    rows="8"
                                                    placeholder="{{ __('custom.inputs.content_' . $locale) }}">{{ old("sections.$sectionIndex.content.description.$locale", $sectionContent['description'][$locale] ?? '') }}</textarea>
                                                <label>{{ __('custom.inputs.content_' . $locale) }}</label>
                                                @error("sections.$sectionIndex.content.description.$locale")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Cards Grid Section Fields --}}
                            @if($sectionConfig['type'] === 'cards_grid')
                                <div class="row">
                                    {{-- Headline --}}
                                    @foreach($locales as $locale)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" 
                                                    class="form-control"
                                                    name="sections[{{ $sectionIndex }}][content][title][{{ $locale }}]"
                                                    value="{{ old("sections.$sectionIndex.content.title.$locale", $sectionContent['title'][$locale] ?? '') }}"
                                                    placeholder="{{ __('custom.inputs.headline_' . $locale) }}">
                                                <label>{{ __('custom.inputs.headline_' . $locale) }}</label>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Description --}}
                                    @foreach($locales as $locale)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" 
                                                    class="form-control"
                                                    name="sections[{{ $sectionIndex }}][content][description][{{ $locale }}]"
                                                    value="{{ old("sections.$sectionIndex.content.description.$locale", $sectionContent['description'][$locale] ?? '') }}"
                                                    placeholder="{{ __('custom.inputs.description_' . $locale) }}">
                                                <label>{{ __('custom.inputs.description_' . $locale) }}</label>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Cards (3 fixed cards) --}}
                                    @php
                                        $cards = $sectionContent['cards'] ?? [
                                            ['title' => ['en' => '', 'ar' => ''], 'description' => ['en' => '', 'ar' => ''], 'icon' => '', 'link' => ''],
                                            ['title' => ['en' => '', 'ar' => ''], 'description' => ['en' => '', 'ar' => ''], 'icon' => '', 'link' => ''],
                                            ['title' => ['en' => '', 'ar' => ''], 'description' => ['en' => '', 'ar' => ''], 'icon' => '', 'link' => ''],
                                        ];
                                    @endphp

                                    @for($cardIndex = 0; $cardIndex < 3; $cardIndex++)
                                        <div class="col-12 mb-4">
                                            <div class="card border">
                                                <div class="card-header bg-light">
                                                    <strong>Card {{ $cardIndex + 1 }}</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        {{-- Card Title --}}
                                                        @foreach($locales as $locale)
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="text" 
                                                                        class="form-control"
                                                                        name="sections[{{ $sectionIndex }}][content][cards][{{ $cardIndex }}][title][{{ $locale }}]"
                                                                        value="{{ old("sections.$sectionIndex.content.cards.$cardIndex.title.$locale", $cards[$cardIndex]['title'][$locale] ?? '') }}"
                                                                        placeholder="Card Title {{ strtoupper($locale) }}">
                                                                    <label>Card Title {{ strtoupper($locale) }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        {{-- Card Description --}}
                                                        @foreach($locales as $locale)
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea 
                                                                        class="form-control"
                                                                        name="sections[{{ $sectionIndex }}][content][cards][{{ $cardIndex }}][description][{{ $locale }}]"
                                                                        rows="3"
                                                                        placeholder="Card Description {{ strtoupper($locale) }}">{{ old("sections.$sectionIndex.content.cards.$cardIndex.description.$locale", $cards[$cardIndex]['description'][$locale] ?? '') }}</textarea>
                                                                    <label>Card Description {{ strtoupper($locale) }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        {{-- Card Icon (MDI class) --}}
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" 
                                                                    class="form-control"
                                                                    name="sections[{{ $sectionIndex }}][content][cards][{{ $cardIndex }}][icon]"
                                                                    value="{{ old("sections.$sectionIndex.content.cards.$cardIndex.icon", $cards[$cardIndex]['icon'] ?? '') }}"
                                                                    placeholder="mdi-star">
                                                                <label>Icon (MDI class, e.g., mdi-star)</label>
                                                            </div>
                                                        </div>

                                                        {{-- Card Link --}}
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" 
                                                                    class="form-control"
                                                                    name="sections[{{ $sectionIndex }}][content][cards][{{ $cardIndex }}][link]"
                                                                    value="{{ old("sections.$sectionIndex.content.cards.$cardIndex.link", $cards[$cardIndex]['link'] ?? '') }}"
                                                                    placeholder="/services/service-one">
                                                                <label>Link URL</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            @endif

                            {{-- CTA Banner Section Fields --}}
                            @if($sectionConfig['type'] === 'cta_banner')
                                <div class="row">
                                    {{-- Title --}}
                                    @foreach($locales as $locale)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" 
                                                    class="form-control"
                                                    name="sections[{{ $sectionIndex }}][content][title][{{ $locale }}]"
                                                    value="{{ old("sections.$sectionIndex.content.title.$locale", $sectionContent['title'][$locale] ?? '') }}"
                                                    placeholder="{{ __('custom.inputs.title_' . $locale) }}">
                                                <label>{{ __('custom.inputs.title_' . $locale) }}</label>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Description --}}
                                    @foreach($locales as $locale)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <textarea 
                                                    class="form-control"
                                                    name="sections[{{ $sectionIndex }}][content][description][{{ $locale }}]"
                                                    rows="4"
                                                    placeholder="{{ __('custom.inputs.description_' . $locale) }}">{{ old("sections.$sectionIndex.content.description.$locale", $sectionContent['description'][$locale] ?? '') }}</textarea>
                                                <label>{{ __('custom.inputs.description_' . $locale) }}</label>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Button Fields --}}
                                    <input type="hidden" name="sections[{{ $sectionIndex }}][has_button]" value="1">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" 
                                                class="form-control"
                                                name="sections[{{ $sectionIndex }}][button_data]"
                                                value="{{ old("sections.$sectionIndex.button_data", $section->button_data ?? '/contact') }}"
                                                placeholder="Button Link">
                                            <label>{{ __('custom.inputs.button_link') }}</label>
                                        </div>
                                    </div>
                                    @foreach($locales as $locale)
                                        <div class="col-md-3 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" 
                                                    class="form-control"
                                                    name="sections[{{ $sectionIndex }}][button_text][{{ $locale }}]"
                                                    value="{{ old("sections.$sectionIndex.button_text.$locale", $section && method_exists($section, 'getTranslation') ? $section->getTranslation('button_text', $locale) : ($section->button_text[$locale] ?? '')) }}"
                                                    placeholder="{{ __('custom.inputs.button_text_' . $locale) }}">
                                                <label>{{ __('custom.inputs.button_text_' . $locale) }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- FAQ Accordion Section Fields --}}
                            @if($sectionConfig['type'] === 'faq_accordion')
                                <div class="row">
                                    {{-- Headline --}}
                                    @foreach($locales as $locale)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" 
                                                    class="form-control"
                                                    name="sections[{{ $sectionIndex }}][content][title][{{ $locale }}]"
                                                    value="{{ old("sections.$sectionIndex.content.title.$locale", $sectionContent['title'][$locale] ?? '') }}"
                                                    placeholder="{{ __('custom.inputs.headline_' . $locale) }}">
                                                <label>{{ __('custom.inputs.headline_' . $locale) }}</label>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- FAQs (4 fixed FAQs) --}}
                                    @php
                                        $faqs = $sectionContent['faqs'] ?? [
                                            ['question' => ['en' => '', 'ar' => ''], 'answer' => ['en' => '', 'ar' => '']],
                                            ['question' => ['en' => '', 'ar' => ''], 'answer' => ['en' => '', 'ar' => '']],
                                            ['question' => ['en' => '', 'ar' => ''], 'answer' => ['en' => '', 'ar' => '']],
                                            ['question' => ['en' => '', 'ar' => ''], 'answer' => ['en' => '', 'ar' => '']],
                                        ];
                                    @endphp

                                    @for($faqIndex = 0; $faqIndex < 4; $faqIndex++)
                                        <div class="col-12 mb-4">
                                            <div class="card border">
                                                <div class="card-header bg-light">
                                                    <strong>FAQ {{ $faqIndex + 1 }}</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        {{-- FAQ Question --}}
                                                        @foreach($locales as $locale)
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="text" 
                                                                        class="form-control"
                                                                        name="sections[{{ $sectionIndex }}][content][faqs][{{ $faqIndex }}][question][{{ $locale }}]"
                                                                        value="{{ old("sections.$sectionIndex.content.faqs.$faqIndex.question.$locale", $faqs[$faqIndex]['question'][$locale] ?? '') }}"
                                                                        placeholder="Question {{ strtoupper($locale) }}">
                                                                    <label>Question {{ strtoupper($locale) }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        {{-- FAQ Answer --}}
                                                        @foreach($locales as $locale)
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-floating form-floating-outline">
                                                                    <textarea 
                                                                        class="form-control"
                                                                        name="sections[{{ $sectionIndex }}][content][faqs][{{ $faqIndex }}][answer][{{ $locale }}]"
                                                                        rows="4"
                                                                        placeholder="Answer {{ strtoupper($locale) }}">{{ old("sections.$sectionIndex.content.faqs.$faqIndex.answer.$locale", $faqs[$faqIndex]['answer'][$locale] ?? '') }}</textarea>
                                                                    <label>Answer {{ strtoupper($locale) }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- Submit Button --}}
                <div class="card-footer">
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
@endsection

@section('js')
<script>
    // Image preview functionality (optional UX enhancement)
    document.querySelectorAll('input[type="file"][accept="image/*"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create or update preview
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


