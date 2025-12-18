@props(['record' => null, 'locales', 'isSection' => false, 'index' => null])

<div class="col-md-12 mt-3">
    <div class="row">
        @foreach ($locales as $locale)
            <div class="col-md-6 mt-3">
                <div class="form-floating form-floating-outline">
                    @php
                        // Fix the value logic for sections
                        if ($isSection) {
                            $oldKey = 'sections.' . $index . '.name.' . $locale;

                            // Handle existing sections with translations
                            if ($record) {
                                if (method_exists($record, 'getTranslation')) {
                                    $defaultValue = $record->getTranslation('name', $locale) ?? '';
                                } elseif (is_object($record)) {
                                    // Handle if name is stored as array or object
                                    if (isset($record->name)) {
                                        if (is_array($record->name)) {
                                            $defaultValue = $record->name[$locale] ?? '';
                                        } elseif (is_object($record->name)) {
                                            $defaultValue = $record->name->{$locale} ?? '';
                                        } else {
                                            $defaultValue = $record->name;
                                        }
                                    } else {
                                        $defaultValue = '';
                                    }
                                } else {
                                    $defaultValue = '';
                                }
                            } else {
                                $defaultValue = '';
                            }
                        } else {
                            // For pages (non-sections)
                            $oldKey = 'name.' . $locale;
                            if ($record && method_exists($record, 'getTranslation')) {
                                $defaultValue = $record->getTranslation('name', $locale) ?? '';
                            } else {
                                $defaultValue = '';
                            }
                        }

                        $value = old($oldKey, $defaultValue);

                        // Fix the name attribute
                        $name = $isSection
                            ? 'sections[' . $index . '][name][' . $locale . ']'
                            : 'name[' . $locale . ']';
                    @endphp

                    <input class="form-control section-name-input" type="text" name="{{ $name }}"
                        value="{{ $value }}" placeholder="{{ __('custom.inputs.name_' . $locale) }}"
                        data-locale="{{ $locale }}">
                    <label>{{ __('custom.inputs.name_' . $locale) }}</label>

                    @error($oldKey)
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>
</div>
