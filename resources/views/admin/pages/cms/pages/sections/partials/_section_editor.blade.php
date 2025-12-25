@php
    $isSubsection = $isSubsection ?? false;
    $subIndex = $subIndex ?? null;
    $namePrefix = $isSubsection 
        ? "sections[{$sectionIndex}][sub_sections][{$subIndex}][content]"
        : "sections[{$sectionIndex}][content]";
    $buttonPrefix = $isSubsection
        ? "sections[{$sectionIndex}][sub_sections][{$subIndex}]"
        : "sections[{$sectionIndex}]";
    
    // Check section type fields to determine what media inputs are needed
    $sectionTypeFields = [];
    if ($section && $section->sectionTypes && $section->sectionTypes->count() > 0) {
        foreach ($section->sectionTypes as $sectionType) {
            if ($sectionType->fields && is_array($sectionType->fields)) {
                $sectionTypeFields = array_merge($sectionTypeFields, $sectionType->fields);
            }
        }
    }
    $sectionTypeFields = array_unique($sectionTypeFields);
    
    // Default section types that typically need images
    $imageSectionTypes = ['hero', 'cta_banner', 'cards_grid'];
    
    $needsImage = in_array('image', $sectionTypeFields) || (empty($sectionTypeFields) && in_array($section->type ?? '', $imageSectionTypes));
    $needsVideo = in_array('video', $sectionTypeFields);
    $needsIcon = in_array('icon', $sectionTypeFields);
    $needsGallery = in_array('gallery', $sectionTypeFields);
    
    // Get existing media
    $desktopImage = $section?->images->where('device', 'desktop')->where('collection_name', 'image_desktop')->first();
    $mobileImage = $section?->images->where('device', 'mobile')->where('collection_name', 'image_mobile')->first();
    $desktopVideo = $section?->videos->where('device', 'desktop')->where('collection_name', 'video_desktop')->first();
    $mobileVideo = $section?->videos->where('device', 'mobile')->where('collection_name', 'video_mobile')->first();
    $desktopPoster = $section?->images->where('device', 'desktop')->where('collection_name', 'video_poster_desktop')->first();
    $mobilePoster = $section?->images->where('device', 'mobile')->where('collection_name', 'video_poster_mobile')->first();
    $icon = $section?->icon;
    $galleryItems = $section?->images->where('collection_name', 'gallery') ?: collect();
@endphp

@switch($section->type)
    @case('hero')
        @include('admin.pages.cms.pages.sections.partials._hero_editor', [
            'section' => $section,
            'sectionContent' => $sectionContent,
            'locales' => $locales,
            'namePrefix' => $namePrefix,
            'buttonPrefix' => $buttonPrefix,
            'sectionIndex' => $sectionIndex,
            'isSubsection' => $isSubsection ?? false,
            'subIndex' => $subIndex ?? null,
        ])
        @break

    @case('rich_text')
        @include('admin.pages.cms.pages.sections.partials._rich_text_editor', [
            'section' => $section,
            'sectionContent' => $sectionContent,
            'locales' => $locales,
            'namePrefix' => $namePrefix,
            'sectionIndex' => $sectionIndex,
            'isSubsection' => $isSubsection ?? false,
            'subIndex' => $subIndex ?? null,
        ])
        @break

    @case('cards_grid')
        @include('admin.pages.cms.pages.sections.partials._cards_grid_editor', [
            'section' => $section,
            'sectionContent' => $sectionContent,
            'locales' => $locales,
            'namePrefix' => $namePrefix,
            'sectionIndex' => $sectionIndex,
            'isSubsection' => $isSubsection ?? false,
            'subIndex' => $subIndex ?? null,
        ])
        @break

    @case('cta_banner')
        @include('admin.pages.cms.pages.sections.partials._cta_banner_editor', [
            'section' => $section,
            'sectionContent' => $sectionContent,
            'locales' => $locales,
            'namePrefix' => $namePrefix,
            'buttonPrefix' => $buttonPrefix,
            'sectionIndex' => $sectionIndex,
            'isSubsection' => $isSubsection ?? false,
            'subIndex' => $subIndex ?? null,
        ])
        @break

    @case('faq_accordion')
        @include('admin.pages.cms.pages.sections.partials._faq_accordion_editor', [
            'section' => $section,
            'sectionContent' => $sectionContent,
            'locales' => $locales,
            'namePrefix' => $namePrefix,
            'sectionIndex' => $sectionIndex,
            'isSubsection' => $isSubsection ?? false,
            'subIndex' => $subIndex ?? null,
        ])
        @break

    @default
        @include('admin.pages.cms.pages.sections.partials._unsupported_editor', [
            'section' => $section,
            'sectionContent' => $sectionContent,
            'namePrefix' => $namePrefix,
            'sectionIndex' => $sectionIndex,
            'isSubsection' => $isSubsection ?? false,
            'subIndex' => $subIndex ?? null,
        ])
@endswitch

{{-- Media Inputs (Images, Videos, Icons, Gallery) --}}
@include('admin.pages.cms.pages.sections.partials._media_inputs', [
    'section' => $section,
    'sectionIndex' => $sectionIndex,
    'isSubsection' => $isSubsection ?? false,
    'subIndex' => $subIndex ?? null,
    'needsImage' => $needsImage,
    'needsVideo' => $needsVideo,
    'needsIcon' => $needsIcon,
    'needsGallery' => $needsGallery,
    'desktopImage' => $desktopImage,
    'mobileImage' => $mobileImage,
    'desktopVideo' => $desktopVideo,
    'mobileVideo' => $mobileVideo,
    'desktopPoster' => $desktopPoster,
    'mobilePoster' => $mobilePoster,
    'icon' => $icon,
    'galleryItems' => $galleryItems,
])

