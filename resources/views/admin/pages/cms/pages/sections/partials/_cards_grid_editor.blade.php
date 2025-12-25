@php
    $cards = $sectionContent['cards'] ?? [
        ['title' => ['en' => '', 'ar' => ''], 'description' => ['en' => '', 'ar' => ''], 'icon' => '', 'link' => ''],
        ['title' => ['en' => '', 'ar' => ''], 'description' => ['en' => '', 'ar' => ''], 'icon' => '', 'link' => ''],
        ['title' => ['en' => '', 'ar' => ''], 'description' => ['en' => '', 'ar' => ''], 'icon' => '', 'link' => ''],
    ];
@endphp

<div class="row">
    {{-- Headline --}}
    @foreach($locales as $locale)
        <div class="col-md-6 mb-3">
            <div class="form-floating form-floating-outline">
                <input type="text" 
                    class="form-control"
                    name="{{ $namePrefix }}[title][{{ $locale }}]"
                    value="{{ $sectionContent['title'][$locale] ?? '' }}"
                    placeholder="{{ __('custom.inputs.headline_' . $locale) }}">
                <label>{{ __('custom.inputs.headline_' . $locale) }}</label>
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

    {{-- Cards (3 fixed cards) --}}
    @for($cardIndex = 0; $cardIndex < 3; $cardIndex++)
        <div class="col-12 mb-4">
            <div class="card border">
                <div class="card-header bg-light">
                    <strong>Card {{ $cardIndex + 1 }}</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Card Title --}}
                        @foreach($locales as $locale)
                            <div class="col-md-6 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" 
                                        class="form-control"
                                        name="{{ $namePrefix }}[cards][{{ $cardIndex }}][title][{{ $locale }}]"
                                        value="{{ $cards[$cardIndex]['title'][$locale] ?? '' }}"
                                        placeholder="Card Title {{ strtoupper($locale) }}">
                                    <label>Card Title {{ strtoupper($locale) }}</label>
                                </div>
                            </div>
                        @endforeach

                        {{-- Card Description --}}
                        @foreach($locales as $locale)
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Card Description {{ strtoupper($locale) }}</label>
                                <textarea 
                                    class="form-control section-description-editor"
                                    id="card-description-{{ $sectionIndex }}-{{ $cardIndex }}-{{ $locale }}{{ isset($isSubsection) && $isSubsection ? '-sub-' . $subIndex : '' }}"
                                    name="{{ $namePrefix }}[cards][{{ $cardIndex }}][description][{{ $locale }}]"
                                    rows="3"
                                    placeholder="Card Description {{ strtoupper($locale) }}">{{ $cards[$cardIndex]['description'][$locale] ?? '' }}</textarea>
                            </div>
                        @endforeach

                        {{-- Card Icon (MDI class) --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" 
                                    class="form-control"
                                    name="{{ $namePrefix }}[cards][{{ $cardIndex }}][icon]"
                                    value="{{ $cards[$cardIndex]['icon'] ?? '' }}"
                                    placeholder="mdi-star">
                                <label>Icon (MDI class, e.g., mdi-star)</label>
                            </div>
                        </div>

                        {{-- Card Link --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" 
                                    class="form-control"
                                    name="{{ $namePrefix }}[cards][{{ $cardIndex }}][link]"
                                    value="{{ $cards[$cardIndex]['link'] ?? '' }}"
                                    placeholder="/services/service-one">
                                <label>Link URL</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>
