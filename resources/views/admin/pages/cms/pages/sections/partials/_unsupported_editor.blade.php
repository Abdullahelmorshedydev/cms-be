@php
    // Get locales if not passed
    if (!isset($locales)) {
        $locales = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLanguagesKeys();
    }
@endphp

@if($sectionContent && is_array($sectionContent) && count($sectionContent) > 0)
    @foreach($sectionContent as $key => $value)
        @if(is_array($value) && !empty(array_intersect(array_keys($value), $locales)))
            {{-- Translation fields --}}
            @foreach($locales as $locale)
                <div class="mb-3">
                    <label class="form-label">{{ ucfirst($key) }} ({{ strtoupper($locale) }})</label>
                    @php
                        $isDescription = strtolower($key) === 'description';
                        $isContent = strtolower($key) === 'content';
                        $isSubtitle = strtolower($key) === 'subtitle';
                        $editorClass = ($isDescription || $isContent) ? 'section-description-editor' : '';
                        $editorId = '';
                        if ($isDescription || $isContent) {
                            $editorId = 'unsupported-' . $key . '-' . (isset($sectionIndex) ? $sectionIndex : '0') . '-' . $locale;
                            if (isset($isSubsection) && $isSubsection && isset($subIndex)) {
                                $editorId .= '-sub-' . $subIndex;
                            }
                        }
                    @endphp
                    @if(!$isSubtitle && (is_string($value[$locale] ?? '') && strlen($value[$locale] ?? '') > 100 || $isDescription || $isContent))
                        <textarea 
                            class="form-control {{ $editorClass }}"
                            @if($editorId) id="{{ $editorId }}" @endif
                            name="{{ $namePrefix }}[{{ $key }}][{{ $locale }}]"
                            rows="4">{{ $value[$locale] ?? '' }}</textarea>
                    @else
                        <input type="text" 
                            class="form-control"
                            name="{{ $namePrefix }}[{{ $key }}][{{ $locale }}]"
                            value="{{ $value[$locale] ?? '' }}"
                            placeholder="{{ ucfirst($key) }} ({{ strtoupper($locale) }})">
                    @endif
                </div>
            @endforeach
        @elseif(is_array($value))
            {{-- Nested arrays (like cards, faqs) --}}
            <div class="mb-3">
                <label class="form-label">{{ ucfirst($key) }}</label>
                <textarea 
                    class="form-control"
                    name="{{ $namePrefix }}[{{ $key }}]"
                    rows="5"
                    placeholder="JSON array">{{ json_encode($value, JSON_PRETTY_PRINT) }}</textarea>
                <small class="text-muted">{{ __('custom.messages.edit_as_json') }}</small>
            </div>
        @else
            {{-- Simple string values --}}
            <div class="mb-3">
                <label class="form-label">{{ ucfirst($key) }}</label>
                <input type="text" 
                    class="form-control"
                    name="{{ $namePrefix }}[{{ $key }}]"
                    value="{{ $value }}"
                    placeholder="{{ ucfirst($key) }}">
            </div>
        @endif
    @endforeach
@else
    {{-- Default: Render content as multi-language fields --}}
    <div class="row">
        @foreach($locales as $locale)
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ __('custom.words.content') }} ({{ strtoupper($locale) }})</label>
                @php
                    $editorId = 'unsupported-content-' . (isset($sectionIndex) ? $sectionIndex : '0') . '-' . $locale;
                    if (isset($isSubsection) && $isSubsection && isset($subIndex)) {
                        $editorId .= '-sub-' . $subIndex;
                    }
                    // Get existing content value
                    $contentValue = '';
                    if ($sectionContent && is_array($sectionContent)) {
                        if (isset($sectionContent['content'][$locale])) {
                            $contentValue = $sectionContent['content'][$locale];
                        } elseif (isset($sectionContent[$locale])) {
                            $contentValue = $sectionContent[$locale];
                        }
                    }
                @endphp
                <textarea 
                    class="form-control section-description-editor"
                    id="{{ $editorId }}"
                    name="{{ $namePrefix }}[content][{{ $locale }}]"
                    rows="5"
                    placeholder="{{ __('custom.words.content') }} ({{ strtoupper($locale) }})">{{ $contentValue }}</textarea>
            </div>
        @endforeach
    </div>
@endif
