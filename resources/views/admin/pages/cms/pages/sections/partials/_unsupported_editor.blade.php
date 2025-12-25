@if($sectionContent && is_array($sectionContent))
    @foreach($sectionContent as $key => $value)
        @if(is_array($value) && (isset($value['en']) || isset($value['ar'])))
            {{-- Translation fields --}}
            @foreach(['en', 'ar'] as $locale)
                <div class="mb-3">
                    <label class="form-label">{{ ucfirst($key) }} ({{ strtoupper($locale) }})</label>
                    @php
                        $isDescription = strtolower($key) === 'description';
                        $isSubtitle = strtolower($key) === 'subtitle';
                        $editorClass = $isDescription ? 'section-description-editor' : '';
                        $editorId = '';
                        if ($isDescription) {
                            $editorId = 'unsupported-description-' . (isset($sectionIndex) ? $sectionIndex : '0') . '-' . $key . '-' . $locale;
                            if (isset($isSubsection) && $isSubsection && isset($subIndex)) {
                                $editorId .= '-sub-' . $subIndex;
                            }
                        }
                    @endphp
                    @if(!$isSubtitle && (is_string($value[$locale] ?? '') && strlen($value[$locale] ?? '') > 100 || $isDescription))
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
    <div class="mb-3">
        <label class="form-label">{{ __('custom.words.content') }}</label>
        <textarea 
            class="form-control"
            name="{{ $namePrefix }}"
            rows="5"
            placeholder="{{ __('custom.words.content') }}">{{ is_string($sectionContent) ? $sectionContent : json_encode($sectionContent, JSON_PRETTY_PRINT) }}</textarea>
    </div>
@endif
