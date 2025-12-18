@props(['record' => null, 'locales'])

<div class="col-md-12 mt-3">
    <div class="row">
        @foreach ($locales as $locale)
            <div class="col-md-6 mt-3">
                <div class="mb-3">
                    <label class="form-label">
                        {{ __('custom.inputs.content_' . $locale) }}
                        @if($locale === 'en')<span class="text-danger">*</span>@endif
                    </label>
                    @php
                        $oldKey = 'content.' . $locale;
                        $defaultValue = $record && method_exists($record, 'getTranslation')
                            ? $record->getTranslation('content', $locale) ?? ''
                            : '';
                        $value = old($oldKey, $defaultValue);
                        $name = 'content[' . $locale . ']';
                    @endphp
                    <textarea id="content_{{ $locale }}"
                        class="form-control blog-ckeditor"
                        name="{{ $name }}"
                        rows="10">{{ $value }}</textarea>
                    @error($oldKey)
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>
</div>

