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
    // Try to find by collection_name first, then fallback to device only
    $desktopImage = null;
    $mobileImage = null;
    if ($section && $section->images && $section->images->count() > 0) {
        // Try to find by collection_name first
        $desktopImage = $section->images->where('device', 'desktop')
            ->where('collection_name', 'image_desktop')
            ->first();
        
        // If not found, get first desktop image (fallback)
        if (!$desktopImage) {
            $desktopImage = $section->images->where('device', 'desktop')->first();
        }
        
        // Try to find by collection_name first
        $mobileImage = $section->images->where('device', 'mobile')
            ->where('collection_name', 'image_mobile')
            ->first();
        
        // If not found, get first mobile image (fallback)
        if (!$mobileImage) {
            $mobileImage = $section->images->where('device', 'mobile')->first();
        }
    }
    
    $desktopVideo = null;
    $mobileVideo = null;
    if ($section && $section->videos) {
        $desktopVideo = $section->videos->where('device', 'desktop')
            ->filter(function($vid) {
                return !$vid->collection_name || $vid->collection_name === 'video_desktop';
            })->first();
        
        $mobileVideo = $section->videos->where('device', 'mobile')
            ->filter(function($vid) {
                return !$vid->collection_name || $vid->collection_name === 'video_mobile';
            })->first();
    }
    
    $desktopPoster = null;
    $mobilePoster = null;
    if ($section && $section->images) {
        $desktopPoster = $section->images->where('device', 'desktop')
            ->filter(function($img) {
                return $img->collection_name === 'video_poster_desktop';
            })->first();
        
        $mobilePoster = $section->images->where('device', 'mobile')
            ->filter(function($img) {
                return $img->collection_name === 'video_poster_mobile';
            })->first();
    }
    
    $icon = $section?->icon;
    $galleryItems = collect();
    if ($section && $section->images) {
        $galleryItems = $section->images->filter(function($img) {
            return $img->collection_name === 'gallery';
        });
    }
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

{{-- Models Manager --}}
@include('admin.pages.cms.pages.sections.partials._models_manager', [
    'section' => $section,
    'sectionIndex' => $sectionIndex,
    'isSubsection' => $isSubsection ?? false,
    'subIndex' => $subIndex ?? null,
    'parentSection' => $parentSection ?? null,
])

