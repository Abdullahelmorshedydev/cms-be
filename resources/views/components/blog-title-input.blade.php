@props(['record' => null, 'locales'])

<div class="col-md-12 mt-3">
    <div class="row">
        @foreach ($locales as $locale)
            <div class="col-md-6 mt-3">
                <div class="form-floating form-floating-outline">
                    @php
                        $oldKey = 'title.' . $locale;
                        $defaultValue = $record && method_exists($record, 'getTranslation')
                            ? $record->getTranslation('title', $locale) ?? ''
                            : '';
                        $value = old($oldKey, $defaultValue);
                        $name = 'title[' . $locale . ']';
                    @endphp

                    <input class="form-control" type="text" name="{{ $name }}"
                        value="{{ $value }}" placeholder="{{ __('custom.inputs.title_' . $locale) }}"
                        {{ $locale === 'en' ? 'required' : '' }}>
                    <label>{{ __('custom.inputs.title_' . $locale) }} @if($locale === 'en')<span class="text-danger">*</span>@endif</label>

                    @error($oldKey)
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>
</div>

