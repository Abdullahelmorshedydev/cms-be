@props(['parentIndex', 'subIndex', 'section' => null])

<div class="accordion-item" data-id="{{ isset($section) ? $section->id : null }}">
    <h2 class="accordion-header position-relative d-flex align-items-center justify-content-between pe-4"
        id="subsection-header-{{ $parentIndex }}-{{ $subIndex }}">
        <button class="accordion-button collapsed flex-grow-1 text-start" type="button" data-bs-toggle="collapse"
            data-bs-target="#subsection-collapse-{{ $parentIndex }}-{{ $subIndex }}" aria-expanded="false"
            aria-controls="subsection-collapse-{{ $parentIndex }}-{{ $subIndex }}">
            {{ __('custom.page.subsection_number', ['number' => $subIndex + 1]) }}
            @if (isset($section) && $section)
                @php
                    $sectionName = '';
                    if (method_exists($section, 'getTranslation')) {
                        $sectionName = $section->getTranslation('name', app()->getLocale()) ?? '';
                    } elseif (is_object($section) && isset($section->name)) {
                        $sectionName = is_array($section->name)
                            ? ($section->name[app()->getLocale()] ?? (array_values($section->name)[0] ?? ''))
                            : $section->name;
                    }
                @endphp
                @if ($sectionName)
                    - {{ $sectionName }}
                @endif
            @endif
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger delete-subsection ms-2" title="{{ __('custom.page.delete_subsection') }}">
            <i class="fa fa-trash"></i>
        </button>
    </h2>
    <div id="subsection-collapse-{{ $parentIndex }}-{{ $subIndex }}" class="accordion-collapse collapse"
        aria-labelledby="subsection-header-{{ $parentIndex }}-{{ $subIndex }}">
        <div class="accordion-body">
            <div class="row">
                {{-- Subsection ID --}}
                @if (isset($section) && $section?->id)
                    <input type="hidden" name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][id]"
                        value="{{ $section->id }}">
                @endif

                {{-- Name --}}
                <div class="col-md-12 mt-3">
                    <div class="row">
                        @foreach ($data['locales'] as $locale)
                            <div class="col-md-6 mt-3">
                                <div class="form-floating form-floating-outline">
                                    @php
                                        $oldKey = 'sections.' . $parentIndex . '.subsections.' . $subIndex . '.name.' . $locale;
                                        $defaultValue = '';
                                        if ($section && method_exists($section, 'getTranslation')) {
                                            $defaultValue = $section->getTranslation('name', $locale) ?? '';
                                        } elseif ($section && is_object($section) && isset($section->name)) {
                                            $defaultValue = is_array($section->name) ? ($section->name[$locale] ?? '') : $section->name;
                                        }
                                        $value = old($oldKey, $defaultValue);
                                    @endphp
                                    <input class="form-control" type="text"
                                        name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][name][{{ $locale }}]"
                                        value="{{ $value }}"
                                        placeholder="{{ __('custom.inputs.name_' . $locale) }}">
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
                                            <textarea class="form-control"
                                                name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][{{ $field['name'] }}][{{ $locale }}]"
                                                placeholder="{{ __('custom.words.' . $field['name'] . '_' . $locale) }}">{{ old("sections.$parentIndex.subsections.$subIndex.{$field['name']}.$locale", $section?->{$field['name']}[$locale] ?? '') }}</textarea>
                                        @else
                                            <input class="form-control" type="text"
                                                name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][{{ $field['name'] }}][{{ $locale }}]"
                                                value="{{ old("sections.$parentIndex.subsections.$subIndex.{$field['name']}.$locale", $section?->{$field['name']}[$locale] ?? '') }}"
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
                        <div class="col-md-12 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][button_type]" class="form-control">
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['buttons_types'] as $type)
                                        <option value="{{ $type['value'] }}"
                                            {{ old("sections.$parentIndex.subsections.$subIndex.button_type", $section?->button_type->value ?? '') == $type['value'] ? 'selected' : '' }}>
                                            {{ $type['lang'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>{{ __('custom.inputs.button_type') }}</label>
                            </div>
                        </div>

                        @foreach ($data['locales'] as $locale)
                            <div class="col-md-6 mt-3">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text"
                                        name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][button_text][{{ $locale }}]"
                                        value="{{ old("sections.$parentIndex.subsections.$subIndex.button_text.$locale", $section?->getTranslation('button_text', $locale) ?? '') }}"
                                        placeholder="{{ __('custom.inputs.button_text_' . $locale) }}">
                                    <label>{{ __('custom.inputs.button_text_' . $locale) }}</label>
                                </div>
                            </div>
                        @endforeach

                        @foreach ($data['locales'] as $locale)
                            <div class="col-md-6 mt-3">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text"
                                        name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][button_url][{{ $locale }}]"
                                        value="{{ old("sections.$parentIndex.subsections.$subIndex.button_url.$locale", $section?->getTranslation('button_url', $locale) ?? '') }}"
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
                        <select class="form-control section-type-select"
                            name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][type]"
                            data-index="{{ $parentIndex }}_subsections_{{ $subIndex }}">
                            <option value="">{{ __('custom.words.choose') }}</option>
                            @foreach ($data['sections_types'] as $type)
                                <option value="{{ $type['value'] }}"
                                    {{ old("sections.$parentIndex.subsections.$subIndex.type", $section?->type->value ?? '') == $type['value'] ? 'selected' : '' }}>
                                    {{ $type['lang'] }}
                                </option>
                            @endforeach
                        </select>
                        <label>{{ __('custom.inputs.type') }}</label>
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-md-6 mt-3">
                    <div class="form-floating form-floating-outline">
                        <select class="form-control"
                            name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][is_active]">
                            <option value="">{{ __('custom.words.choose') }}</option>
                            @foreach ($data['status'] as $stat)
                                <option value="{{ $stat['value'] }}"
                                    {{ old("sections.$parentIndex.subsections.$subIndex.is_active", $section?->is_active->value ?? '') == $stat['value'] ? 'selected' : '' }}>
                                    {{ $stat['lang'] }}
                                </option>
                            @endforeach
                        </select>
                        <label>{{ __('custom.inputs.is_active') }}</label>
                    </div>
                </div>

                {{-- Content fields --}}
                @php
                    $sectionTypeValue = $section?->type->value ?? '';
                @endphp
                <div class="col-12 mt-3 section-content"
                    id="section-content-{{ $parentIndex }}_subsections_{{ $subIndex }}"
                    style="{{ in_array($sectionTypeValue, [2, 8, 9, 10, 11, 12]) ? '' : 'display:none;' }}">
                    @foreach ($data['locales'] as $locale)
                        <div class="form-floating form-floating-outline mt-2">
                            <input type="text" class="form-control"
                                name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][content][title][{{ $locale }}]"
                                value="{{ old("sections.$parentIndex.subsections.$subIndex.content.title.$locale", $section?->content['title'][$locale] ?? '') }}"
                                placeholder="{{ __('custom.inputs.title_' . $locale) }}">
                            <label>{{ __('custom.inputs.title_' . $locale) }}</label>
                        </div>
                    @endforeach

                    @foreach ($data['locales'] as $locale)
                        <div class="form-floating form-floating-outline mt-2">
                            <input type="text" class="form-control"
                                name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][content][subtitle][{{ $locale }}]"
                                value="{{ old("sections.$parentIndex.subsections.$subIndex.content.subtitle.$locale", $section?->content['subtitle'][$locale] ?? '') }}"
                                placeholder="{{ __('custom.inputs.subtitle_' . $locale) }}">
                            <label>{{ __('custom.inputs.subtitle_' . $locale) }}</label>
                        </div>
                    @endforeach

                    @foreach ($data['locales'] as $locale)
                        <div class="form-floating form-floating-outline mt-2">
                            <textarea class="form-control"
                                name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][content][description][{{ $locale }}]"
                                placeholder="{{ __('custom.inputs.description_' . $locale) }}">{{ old("sections.$parentIndex.subsections.$subIndex.content.description.$locale", $section?->content['description'][$locale] ?? '') }}</textarea>
                            <label>{{ __('custom.inputs.description_' . $locale) }}</label>
                        </div>
                    @endforeach
                </div>

                @php
                    $desktopImage = $section?->images->where('device', 'desktop')->where('collection_name', 'image_desktop')->first();
                    $mobileImage = $section?->images->where('device', 'mobile')->where('collection_name', 'image_mobile')->first();
                    $desktopVideo = $section?->videos->where('device', 'desktop')->where('collection_name', 'video_desktop')->first();
                    $mobileVideo = $section?->videos->where('device', 'mobile')->where('collection_name', 'video_mobile')->first();
                    $desktopPoster = $section?->images->where('device', 'desktop')->where('collection_name', 'video_poster_desktop')->first();
                    $mobilePoster = $section?->images->where('device', 'mobile')->where('collection_name', 'video_poster_mobile')->first();
                    $file = $section?->file;
                    $icon = $section?->icon;
                    $galleryItems = $section?->gallery ?? collect();
                @endphp

                {{-- Images --}}
                <div class="col-12 mt-3 section-image" id="section-image-{{ $parentIndex }}_subsections_{{ $subIndex }}"
                    style="{{ in_array($sectionTypeValue, [1, 8]) ? '' : 'display:none;' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <x-project-media-input
                                :name="'sections[' . $parentIndex . '][subsections][' . $subIndex . '][image][desktop]'"
                                :label="__('custom.inputs.image_desktop')"
                                type="image"
                                :existingMedia="$desktopImage"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-project-media-input
                                :name="'sections[' . $parentIndex . '][subsections][' . $subIndex . '][image][mobile]'"
                                :label="__('custom.inputs.image_mobile')"
                                type="image"
                                :existingMedia="$mobileImage"
                            />
                        </div>
                    </div>
                </div>

                {{-- Videos --}}
                <div class="col-12 mt-3 section-video" id="section-video-{{ $parentIndex }}_subsections_{{ $subIndex }}"
                    style="{{ in_array($sectionTypeValue, [4, 10]) ? '' : 'display:none;' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <x-project-media-input
                                :name="'sections[' . $parentIndex . '][subsections][' . $subIndex . '][video][desktop]'"
                                :label="__('custom.inputs.video_desktop')"
                                type="video"
                                :existingMedia="$desktopVideo"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-project-media-input
                                :name="'sections[' . $parentIndex . '][subsections][' . $subIndex . '][video][mobile]'"
                                :label="__('custom.inputs.video_mobile')"
                                type="video"
                                :existingMedia="$mobileVideo"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-project-media-input
                                :name="'sections[' . $parentIndex . '][subsections][' . $subIndex . '][video][poster][desktop]'"
                                :label="__('custom.inputs.poster_desktop')"
                                type="image"
                                :existingMedia="$desktopPoster"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-project-media-input
                                :name="'sections[' . $parentIndex . '][subsections][' . $subIndex . '][video][poster][mobile]'"
                                :label="__('custom.inputs.poster_mobile')"
                                type="image"
                                :existingMedia="$mobilePoster"
                            />
                        </div>
                    </div>
                </div>

                {{-- Icon --}}
                <div class="col-12 mt-3 section-icon" id="section-icon-{{ $parentIndex }}_subsections_{{ $subIndex }}"
                    style="{{ in_array($sectionTypeValue, [7, 12]) ? '' : 'display:none;' }}">
                    <x-project-media-input
                        :name="'sections[' . $parentIndex . '][subsections][' . $subIndex . '][icon]'"
                        :label="__('custom.inputs.icon')"
                        type="icon"
                        :existingMedia="$icon"
                    />
                </div>

                {{-- Files --}}
                <div class="col-12 mt-3 section-file" id="section-file-{{ $parentIndex }}_subsections_{{ $subIndex }}"
                    style="{{ in_array($sectionTypeValue, [5, 11]) ? '' : 'display:none;' }}">
                    <x-project-media-input
                        :name="'sections[' . $parentIndex . '][subsections][' . $subIndex . '][file]'"
                        :label="__('custom.inputs.file')"
                        type="file"
                        :existingMedia="$file"
                    />
                </div>

                {{-- Gallery --}}
                <div class="col-12 mt-3 section-gallery" id="section-gallery-{{ $parentIndex }}_subsections_{{ $subIndex }}"
                    style="{{ in_array($sectionTypeValue, [6, 9]) ? '' : 'display:none;' }}">
                    <x-project-media-input
                        :name="'sections[' . $parentIndex . '][subsections][' . $subIndex . '][gallery][]'"
                        :label="__('custom.inputs.gallery')"
                        type="image"
                        :existingMedia="$galleryItems"
                        :multiple="true"
                    />
                </div>

                <input type="hidden" name="sections[{{ $parentIndex }}][subsections][{{ $subIndex }}][order]"
                    value="{{ $subIndex }}">
            </div>
        </div>
    </div>
</div>
