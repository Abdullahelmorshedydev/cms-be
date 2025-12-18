@props(['record' => null, 'locales'])

<div class="col-md-12 mt-3">
    <div class="row">
        @foreach ($locales as $locale)
            <div class="col-md-6 mt-3">
                <div class="form-floating form-floating-outline">
                    @php
                        $oldKey = 'excerpt.' . $locale;
                        $defaultValue = $record && method_exists($record, 'getTranslation')
                            ? $record->getTranslation('excerpt', $locale) ?? ''
                            : '';
                        $value = old($oldKey, $defaultValue);
                        $name = 'excerpt[' . $locale . ']';
                    @endphp

                    <textarea class="form-control" name="{{ $name }}"
                        placeholder="{{ __('custom.inputs.excerpt_' . $locale) }}"
                        rows="3" style="height: 100px;">{{ $value }}</textarea>
                    <label>{{ __('custom.inputs.excerpt_' . $locale) }}</label>

                    @error($oldKey)
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>
</div>

