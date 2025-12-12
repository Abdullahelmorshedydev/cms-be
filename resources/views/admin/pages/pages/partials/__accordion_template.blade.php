<template id="accordionTemplate">
    <div class="accordion-item">
        <h2 class="accordion-header position-relative d-flex align-items-center justify-content-between pe-4"
            id="section-header-__INDEX__">
            <button class="accordion-button collapsed flex-grow-1 text-start" type="button" data-bs-toggle="collapse"
                data-bs-target="#section-collapse-__INDEX__" aria-expanded="false"
                aria-controls="section-collapse-__INDEX__">
                {{ __('custom.page.section') }} __INDEX_PLUS_ONE__
            </button>
            {{-- Delete button --}}
            <button type="button" class="btn btn-sm btn-outline-danger delete-section ms-2" title="{{ __('custom.page.delete_section') }}">
                <i class="fa fa-trash"></i>
            </button>
        </h2>
        <div id="section-collapse-__INDEX__" class="accordion-collapse collapse"
            aria-labelledby="section-header-__INDEX__" data-bs-parent="#dynamicAccordion">
            <div class="accordion-body">
                <div class="row">
                    {{-- Name Fields --}}
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            @foreach ($data['locales'] as $locale)
                                <div class="col-md-6 mt-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control section-name-input" type="text"
                                            name="sections[__INDEX__][name][{{ $locale }}]"
                                            placeholder="{{ __('custom.inputs.name_' . $locale) }}"
                                            data-locale="{{ $locale }}">
                                        <label>{{ __('custom.inputs.name_' . $locale) }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Meta Fields --}}
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            @php
                                $metaFields = [
                                    ['name' => 'meta_title', 'type' => 'text'],
                                    ['name' => 'meta_keywords', 'type' => 'text'],
                                    ['name' => 'meta_description', 'type' => 'textarea'],
                                ];
                            @endphp
                            @foreach ($metaFields as $field)
                                @foreach ($data['locales'] as $locale)
                                    <div class="col-md-6 mt-3">
                                        <div class="form-floating form-floating-outline">
                                            @if ($field['type'] === 'textarea')
                                                <textarea class="form-control" name="sections[__INDEX__][{{ $field['name'] }}][{{ $locale }}]"
                                                    placeholder="{{ __('custom.words.' . $field['name'] . '_' . $locale) }}"></textarea>
                                            @else
                                                <input class="form-control" type="text"
                                                    name="sections[__INDEX__][{{ $field['name'] }}][{{ $locale }}]"
                                                    placeholder="{{ __('custom.words.' . $field['name'] . '_' . $locale) }}">
                                            @endif
                                            <label>{{ __('custom.words.' . $field['name'] . '_' . $locale) }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    {{-- Button Fields --}}
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            {{-- Button Type --}}
                            <div class="col-md-12 mt-3">
                                <div class="form-floating form-floating-outline">
                                    <select name="sections[__INDEX__][button_type]" class="form-control">
                                        <option value="">{{ __('custom.words.choose') }}</option>
                                        @foreach ($data['buttons_types'] as $type)
                                            <option value="{{ $type['value'] }}">
                                                {{ $type['lang'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>{{ __(key: 'custom.inputs.button_type') }}</label>
                                </div>
                            </div>

                            {{-- Button Text (EN / AR) --}}
                            @foreach ($data['locales'] as $locale)
                                <div class="col-md-6 mt-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text"
                                            name="sections[__INDEX__][button_text][{{ $locale }}]"
                                            placeholder="{{ __('custom.inputs.button_text_' . $locale) }}">
                                        <label>{{ __('custom.inputs.button_text_' . $locale) }}</label>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Button URL (EN / AR) --}}
                            @foreach ($data['locales'] as $locale)
                                <div class="col-md-6 mt-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text"
                                            name="sections[__INDEX__][button_url][{{ $locale }}]"
                                            placeholder="{{ __('custom.inputs.button_url_' . $locale) }}">
                                        <label>{{ __('custom.inputs.button_url_' . $locale) }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Type --}}
                    <div class="col-md-6 mt-3">
                        <div class="form-floating form-floating-outline">
                            <select class="form-control section-type-select" name="sections[__INDEX__][type]"
                                data-index="__INDEX__">
                                <option value="">{{ __('custom.words.choose') }}</option>
                                @foreach ($data['sections_types'] as $type)
                                    <option value="{{ $type['value'] }}">{{ $type['lang'] }}</option>
                                @endforeach
                            </select>
                            <label>{{ __('custom.inputs.type') }}</label>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mt-3">
                        <div class="form-floating form-floating-outline">
                            <select class="form-control" name="sections[__INDEX__][is_active]">
                                <option value="">{{ __('custom.words.choose') }}</option>
                                @foreach ($data['status'] as $stat)
                                    <option value="{{ $stat['value'] }}">{{ $stat['lang'] }}</option>
                                @endforeach
                            </select>
                            <label>{{ __('custom.inputs.is_active') }}</label>
                        </div>
                    </div>

                    {{-- Text Content --}}
                    <div class="col-12 mt-3 section-content" id="section-content-__INDEX__" style="display:none;">
                        @foreach ($data['locales'] as $locale)
                            <div class="form-floating form-floating-outline mt-2">
                                <input type="text" class="form-control"
                                    name="sections[__INDEX__][content][title][{{ $locale }}]"
                                    placeholder="{{ __('custom.inputs.title_' . $locale) }}">
                                <label>{{ __('custom.inputs.title_' . $locale) }}</label>
                            </div>
                        @endforeach

                        @foreach ($data['locales'] as $locale)
                            <div class="form-floating form-floating-outline mt-2">
                                <input type="text" class="form-control"
                                    name="sections[__INDEX__][content][subtitle][{{ $locale }}]"
                                    placeholder="{{ __('custom.inputs.subtitle_' . $locale) }}">
                                <label>{{ __('custom.inputs.subtitle_' . $locale) }}</label>
                            </div>
                        @endforeach

                        @foreach ($data['locales'] as $locale)
                            <div class="form-floating form-floating-outline mt-2">
                                <textarea class="form-control" name="sections[__INDEX__][content][description][{{ $locale }}]"
                                    placeholder="{{ __('custom.inputs.description_' . $locale) }}"></textarea>
                                <label>{{ __('custom.inputs.description_' . $locale) }}</label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Images --}}
                    <div class="col-12 mt-3 section-image" id="section-image-__INDEX__" style="display:none;">
                        <label>{{ __('custom.inputs.image_desktop') }}</label>
                        <input class="form-control mb-2" type="file" name="sections[__INDEX__][image][desktop]">
                        <div id="preview-image-desktop-__INDEX__"></div>

                        <label>{{ __('custom.inputs.image_mobile') }}</label>
                        <input class="form-control" type="file" name="sections[__INDEX__][image][mobile]">
                        <div id="preview-image-mobile-__INDEX__"></div>
                    </div>

                    {{-- Videos --}}
                    <div class="col-12 mt-3 section-video" id="section-video-__INDEX__" style="display:none;">
                        <label>{{ __('custom.inputs.video_desktop') }}</label>
                        <input class="form-control mb-2" type="file" name="sections[__INDEX__][video][desktop]">
                        <div id="preview-video-desktop-__INDEX__"></div>

                        <label>{{ __('custom.inputs.video_mobile') }}</label>
                        <input class="form-control mb-2" type="file" name="sections[__INDEX__][video][mobile]">
                        <div id="preview-video-mobile-__INDEX__"></div>

                        <label>{{ __('custom.inputs.poster_desktop') }}</label>
                        <input class="form-control mb-2" type="file" name="sections[__INDEX__][video][poster][desktop]">
                        <div id="preview-video-poster-desktop-__INDEX__"></div>

                        <label>{{ __('custom.inputs.poster_mobile') }}</label>
                        <input class="form-control" type="file" name="sections[__INDEX__][video][poster][mobile]">
                        <div id="preview-video-poster-mobile-__INDEX__"></div>
                    </div>

                    {{-- Icon --}}
                    <div class="col-12 mt-3 section-icon" id="section-icon-__INDEX__" style="display:none;">
                        <label>{{ __('custom.inputs.icon') }}</label>
                        <input class="form-control" type="file" name="sections[__INDEX__][icon]">
                        <div id="preview-icon-__INDEX__"></div>
                    </div>

                    {{-- Files --}}
                    <div class="col-12 mt-3 section-file" id="section-file-__INDEX__" style="display:none;">
                        <label>{{ __('custom.inputs.file') }}</label>
                        <input class="form-control" type="file" name="sections[__INDEX__][file]">
                        <div id="preview-file-__INDEX__"></div>
                    </div>

                    {{-- Gallery --}}
                    <div class="col-12 mt-3 section-gallery" id="section-gallery-__INDEX__" style="display:none;">
                        <label>{{ __('custom.inputs.gallery') }}</label>
                        <input class="form-control" type="file" name="sections[__INDEX__][gallery][]" multiple>
                        <div id="preview-gallery-__INDEX__" class="d-flex flex-wrap gap-2 mt-2"></div>
                    </div>
                </div>

                <input type="hidden" name="sections[__INDEX__][order]" value="__INDEX__">

                <div class="mt-3">
                    <h6>{{ __('custom.page.subsections') }}</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary add-subsection-btn"
                        data-parent-index="__INDEX__">
                        {{ __('custom.page.add_subsection') }}
                    </button>
                    <div class="accordion mt-2 subsection-container" id="subsection-container-__INDEX__"></div>
                </div>
            </div>
        </div>
    </div>
</template>
