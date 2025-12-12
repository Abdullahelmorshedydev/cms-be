@props(['index', 'section' => null])

<div class="row">
    {{-- Section ID --}}
    @if (isset($section) && $section?->id)
        <input type="hidden" name="sections[{{ $index }}][id]" value="{{ $section->id }}">
    @endif

    {{-- Name --}}
    <x-input-name :index="$index" :record="$section ?? null" :locales="$data['locales']" :isSection="true" />

    {{-- Meta Data --}}
    <x-meta-fields :index="$index" :record="$section ?? null" :locales="$data['locales']" :isSection="true" />

    {{-- Button Fields --}}
    <x-button-fields :index="$index" :record="$section ?? null" :buttonTypes="$data['buttons_types']" :locales="$data['locales']" />

    {{-- Status --}}
    <div class="col-md-6 mt-3">
        <div class="form-floating form-floating-outline">
            <select class="form-control" name="sections[{{ $index }}][is_active]">
                <option value="">{{ __('custom.words.choose') }}</option>
                @foreach ($data['status'] as $stat)
                    <option value="{{ $stat['value'] }}"
                        {{ old("sections.$index.is_active", $section?->is_active->value ?? '') == $stat['value'] ? 'selected' : '' }}>
                        {{ $stat['lang'] }}
                    </option>
                @endforeach
            </select>
            <label>{{ __('custom.inputs.is_active') }}</label>
        </div>
    </div>

    {{-- Type --}}
    <div class="col-md-6 mt-3">
        <div class="form-floating form-floating-outline">
            <select class="form-control section-type-select" name="sections[{{ $index }}][type]"
                data-index="{{ $index }}">
                <option value="">{{ __('custom.words.choose') }}</option>
                @foreach ($data['sections_types'] as $type)
                    <option value="{{ $type['value'] }}"
                        {{ old("sections.$index.type", $section?->type->value ?? '') == $type['value'] ? 'selected' : '' }}>
                        {{ $type['lang'] }}
                    </option>
                @endforeach
            </select>
            <label>{{ __('custom.inputs.type') }}</label>
        </div>
    </div>

    {{-- Text Content --}}
    <div class="col-12 mt-3 section-content" id="section-content-{{ $index }}"
        style="{{ in_array($section?->type->value ?? '', [2, 8, 9, 10, 11, 12]) ? '' : 'display:none;' }}">
        @foreach ($data['locales'] as $locale)
            <div class="form-floating form-floating-outline mt-2">
                <input type="text" class="form-control"
                    name="sections[{{ $index }}][content][title][{{ $locale }}]"
                    value="{{ old("sections.$index.content.title.$locale", $section?->content['title'][$locale] ?? '') }}"
                    placeholder="{{ __('custom.inputs.title_' . $locale) }}">
                <label>{{ __('custom.inputs.title_' . $locale) }}</label>
            </div>
        @endforeach

        @foreach ($data['locales'] as $locale)
            <div class="form-floating form-floating-outline mt-2">
                <input type="text" class="form-control"
                    name="sections[{{ $index }}][content][subtitle][{{ $locale }}]"
                    value="{{ old("sections.$index.content.subtitle.$locale", $section?->content['subtitle'][$locale] ?? '') }}"
                    placeholder="{{ __('custom.inputs.subtitle_' . $locale) }}">
                <label>{{ __('custom.inputs.subtitle_' . $locale) }}</label>
            </div>
        @endforeach

        @foreach ($data['locales'] as $locale)
            <div class="form-floating form-floating-outline mt-2">
                <textarea class="form-control" name="sections[{{ $index }}][content][description][{{ $locale }}]"
                    placeholder="{{ __('custom.inputs.description_' . $locale) }}">{{ old("sections.$index.content.description.$locale", $section?->content['description'][$locale] ?? '') }}</textarea>
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
    <div class="col-12 mt-3 section-image" id="section-image-{{ $index }}"
        style="{{ in_array($section?->type->value ?? '', [1, 8]) ? '' : 'display:none;' }}">
        <div class="row">
            <div class="col-md-6">
                <x-project-media-input
                    :name="'sections[' . $index . '][image][desktop]'"
                    :label="__('custom.inputs.image_desktop')"
                    type="image"
                    :existingMedia="$desktopImage"
                />
            </div>
            <div class="col-md-6">
                <x-project-media-input
                    :name="'sections[' . $index . '][image][mobile]'"
                    :label="__('custom.inputs.image_mobile')"
                    type="image"
                    :existingMedia="$mobileImage"
                />
            </div>
        </div>
    </div>

    {{-- Videos --}}
    <div class="col-12 mt-3 section-video" id="section-video-{{ $index }}"
        style="{{ in_array($section?->type->value ?? '', [4, 10]) ? '' : 'display:none;' }}">
        <div class="row">
            <div class="col-md-6">
                <x-project-media-input
                    :name="'sections[' . $index . '][video][desktop]'"
                    :label="__('custom.inputs.video_desktop')"
                    type="video"
                    :existingMedia="$desktopVideo"
                />
            </div>
            <div class="col-md-6">
                <x-project-media-input
                    :name="'sections[' . $index . '][video][mobile]'"
                    :label="__('custom.inputs.video_mobile')"
                    type="video"
                    :existingMedia="$mobileVideo"
                />
            </div>
            <div class="col-md-6">
                <x-project-media-input
                    :name="'sections[' . $index . '][video][poster][desktop]'"
                    :label="__('custom.inputs.poster_desktop')"
                    type="image"
                    :existingMedia="$desktopPoster"
                />
            </div>
            <div class="col-md-6">
                <x-project-media-input
                    :name="'sections[' . $index . '][video][poster][mobile]'"
                    :label="__('custom.inputs.poster_mobile')"
                    type="image"
                    :existingMedia="$mobilePoster"
                />
            </div>
        </div>
    </div>

    {{-- Icon --}}
    <div class="col-12 mt-3 section-icon" id="section-icon-{{ $index }}"
        style="{{ in_array($section?->type->value ?? '', [7, 12]) ? '' : 'display:none;' }}">
        <x-project-media-input
            :name="'sections[' . $index . '][icon]'"
            :label="__('custom.inputs.icon')"
            type="icon"
            :existingMedia="$icon"
        />
    </div>

    {{-- Files --}}
    <div class="col-12 mt-3 section-file" id="section-file-{{ $index }}"
        style="{{ in_array($section?->type->value ?? '', [5, 11]) ? '' : 'display:none;' }}">
        <x-project-media-input
            :name="'sections[' . $index . '][file]'"
            :label="__('custom.inputs.file')"
            type="file"
            :existingMedia="$file"
        />
    </div>

    {{-- Gallery --}}
    <div class="col-12 mt-3 section-gallery" id="section-gallery-{{ $index }}"
        style="{{ in_array($section?->type->value ?? '', [6, 9]) ? '' : 'display:none;' }}">
        <x-project-media-input
            :name="'sections[' . $index . '][gallery][]'"
            :label="__('custom.inputs.gallery')"
            type="image"
            :existingMedia="$galleryItems"
            :multiple="true"
        />
    </div>
</div>
