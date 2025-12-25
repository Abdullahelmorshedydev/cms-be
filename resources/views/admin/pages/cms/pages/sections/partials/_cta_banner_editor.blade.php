<div class="row">
    {{-- Title --}}
    @foreach($locales as $locale)
        <div class="col-md-6 mb-3">
            <div class="form-floating form-floating-outline">
                <input type="text" 
                    class="form-control"
                    name="{{ $namePrefix }}[title][{{ $locale }}]"
                    value="{{ $sectionContent['title'][$locale] ?? '' }}"
                    placeholder="{{ __('custom.inputs.title_' . $locale) }}">
                <label>{{ __('custom.inputs.title_' . $locale) }}</label>
            </div>
        </div>
    @endforeach

    {{-- Description --}}
    @foreach($locales as $locale)
        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('custom.inputs.description_' . $locale) }}</label>
            <textarea 
                class="form-control section-description-editor"
                id="section-description-{{ $sectionIndex }}-{{ $locale }}{{ isset($isSubsection) && $isSubsection ? '-sub-' . $subIndex : '' }}"
                name="{{ $namePrefix }}[description][{{ $locale }}]"
                rows="4"
                placeholder="{{ __('custom.inputs.description_' . $locale) }}">{{ $sectionContent['description'][$locale] ?? '' }}</textarea>
        </div>
    @endforeach

    {{-- Button Fields (only for main sections, not subsections) --}}
    @if(!isset($isSubsection) || !$isSubsection)
        <input type="hidden" name="sections[{{ $sectionIndex }}][has_button]" value="1">
        <div class="col-md-6 mb-3">
            <div class="form-floating form-floating-outline">
                <input type="text" 
                    class="form-control"
                    name="{{ $buttonPrefix }}[button_data]"
                    value="{{ $section->button_data ?? '/contact' }}"
                    placeholder="Button Link">
                <label>{{ __('custom.inputs.button_link') }}</label>
            </div>
        </div>
        @foreach($locales as $locale)
            <div class="col-md-3 mb-3">
                <div class="form-floating form-floating-outline">
                    <input type="text" 
                        class="form-control"
                        name="{{ $buttonPrefix }}[button_text][{{ $locale }}]"
                        value="{{ $section && method_exists($section, 'getTranslation') ? $section->getTranslation('button_text', $locale) : ($section->button_text[$locale] ?? '') }}"
                        placeholder="{{ __('custom.inputs.button_text_' . $locale) }}">
                    <label>{{ __('custom.inputs.button_text_' . $locale) }}</label>
                </div>
            </div>
        @endforeach
    @endif
</div>
