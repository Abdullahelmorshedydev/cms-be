@props(['index', 'buttonTypes', 'record' => null, 'locales'])

<div class="col-md-12 mt-3">
    <div class="row">
        {{-- Button Type --}}
        <div class="col-md-12 mt-3">
            <div class="form-floating form-floating-outline">
                <select name="sections[{{ $index }}][button_type]" class="form-control">
                    <option value="">{{ __('custom.words.choose') }}</option>
                    @foreach ($buttonTypes as $type)
                        <option value="{{ $type['value'] }}"
                            {{ old("sections.$index.button_type", $record?->button_type->value ?? '') == $type['value'] ? 'selected' : '' }}>
                            {{ $type['lang'] }}
                        </option>
                    @endforeach
                </select>
                <label>{{ __(key: 'custom.inputs.button_type') }}</label>
            </div>
        </div>

        {{-- Button Text (EN / AR) --}}
        @foreach ($locales as $locale)
            <div class="col-md-6 mt-3">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text"
                        name="sections[{{ $index }}][button_text][{{ $locale }}]"
                        value="{{ old("sections.$index.button_text." . $locale, $record?->getTranslation('button_text', $locale) ?? '') }}"
                        placeholder="{{ __('custom.inputs.button_text_' . $locale) }}">
                    <label>{{ __('custom.inputs.button_text_' . $locale) }}</label>
                </div>
            </div>
        @endforeach

        {{-- Button URL (EN / AR) --}}
        @foreach ($locales as $locale)
            <div class="col-md-6 mt-3">
                <div class="form-floating form-floating-outline">
                    <input class="form-control" type="text"
                        name="sections[{{ $index }}][button_url][{{ $locale }}]"
                        value="{{ old("sections.$index.button_url." . $locale, $record?->getTranslation('button_url', $locale) ?? '') }}"
                        placeholder="{{ __('custom.inputs.button_url_' . $locale) }}">
                    <label>{{ __('custom.inputs.button_url_' . $locale) }}</label>
                </div>
            </div>
        @endforeach
    </div>
</div>
