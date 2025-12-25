<div class="row">
    @foreach($locales as $locale)
        <div class="col-md-6 mb-3">
            <label class="form-label">{{ __('custom.inputs.content_' . $locale) }}</label>
            <textarea 
                class="form-control section-description-editor"
                id="section-description-{{ $sectionIndex }}-{{ $locale }}{{ isset($isSubsection) && $isSubsection ? '-sub-' . $subIndex : '' }}"
                name="{{ $namePrefix }}[description][{{ $locale }}]"
                rows="8"
                placeholder="{{ __('custom.inputs.content_' . $locale) }}">{{ $sectionContent['description'][$locale] ?? '' }}</textarea>
        </div>
    @endforeach
</div>
