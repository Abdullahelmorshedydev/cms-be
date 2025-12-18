@props(['record' => null, 'locales', 'isSection' => false, 'index' => null])

@php
    $metaFields = [
        ['name' => 'meta_title', 'type' => 'text'],
        ['name' => 'meta_keywords', 'type' => 'text'],
        ['name' => 'meta_description', 'type' => 'textarea'],
    ];
@endphp

<div class="col-md-12 mt-3">
    <div class="row">
        @foreach ($metaFields as $field)
            @foreach ($locales as $locale)
                <div class="col-md-6 mt-3">
                    <div class="form-floating form-floating-outline">
                        @php
                            // Fix the old() key and name attribute for sections
                            if ($isSection) {
                                $oldKey = 'sections.' . $index . '.' . $field['name'] . '.' . $locale;
                                $name = 'sections[' . $index . '][' . $field['name'] . '][' . $locale . ']';
                                
                                // Handle existing section records
                                if ($record) {
                                    if (method_exists($record, 'getTranslation')) {
                                        $defaultValue = $record->getTranslation($field['name'], $locale) ?? '';
                                    } elseif (is_object($record) && isset($record->{$field['name']})) {
                                        if (is_array($record->{$field['name']})) {
                                            $defaultValue = $record->{$field['name']}[$locale] ?? '';
                                        } else {
                                            $defaultValue = $record->{$field['name']};
                                        }
                                    } else {
                                        $defaultValue = '';
                                    }
                                } else {
                                    $defaultValue = '';
                                }
                            } else {
                                $oldKey = $field['name'] . '.' . $locale;
                                $name = $field['name'] . '[' . $locale . ']';
                                
                                if ($record && method_exists($record, 'getTranslation')) {
                                    $defaultValue = $record->getTranslation($field['name'], $locale) ?? '';
                                } else {
                                    $defaultValue = $record->{$field['name']} ?? '';
                                }
                            }
                            
                            $value = old($oldKey, $defaultValue);
                        @endphp

                        @if ($field['type'] === 'textarea')
                            <textarea class="form-control"
                                name="{{ $name }}"
                                placeholder="{{ __('custom.words.' . $field['name'] . '_' . $locale) }}">{{ $value }}</textarea>
                        @else
                            <input class="form-control" type="text"
                                name="{{ $name }}"
                                value="{{ $value }}"
                                placeholder="{{ __('custom.words.' . $field['name'] . '_' . $locale) }}">
                        @endif

                        <label>{{ __('custom.words.' . $field['name'] . '_' . $locale) }}</label>

                        @error($oldKey)
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
