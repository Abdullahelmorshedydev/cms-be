@php
    $isSubsection = $isSubsection ?? false;
    $subIndex = $subIndex ?? null;
    $needsImage = $needsImage ?? false;
    $needsVideo = $needsVideo ?? false;
    $needsIcon = $needsIcon ?? false;
    $needsGallery = $needsGallery ?? false;
    
    // Determine media input names based on if it's a subsection
    $imagePrefix = $isSubsection 
        ? "sections[{$sectionIndex}][sub_sections][{$subIndex}][image]"
        : "sections[{$sectionIndex}][image]";
    $videoPrefix = $isSubsection 
        ? "sections[{$sectionIndex}][sub_sections][{$subIndex}][video]"
        : "sections[{$sectionIndex}][video]";
    $iconPrefix = $isSubsection 
        ? "sections[{$sectionIndex}][sub_sections][{$subIndex}][icon]"
        : "sections[{$sectionIndex}][icon]";
    $galleryPrefix = $isSubsection 
        ? "sections[{$sectionIndex}][sub_sections][{$subIndex}][gallery]"
        : "sections[{$sectionIndex}][gallery]";
    
    // Ensure media variables are set
    $desktopImage = $desktopImage ?? null;
    $mobileImage = $mobileImage ?? null;
    $desktopVideo = $desktopVideo ?? null;
    $mobileVideo = $mobileVideo ?? null;
    $desktopPoster = $desktopPoster ?? null;
    $mobilePoster = $mobilePoster ?? null;
    $icon = $icon ?? null;
    $galleryItems = $galleryItems ?? collect();
@endphp

{{-- Images (Desktop & Mobile) --}}
@if($needsImage)
    <div class="col-12 mt-3">
        <div class="row">
            <div class="col-md-6">
                <x-project-media-input
                    :name="$imagePrefix . '[desktop]'"
                    :label="__('custom.inputs.image_desktop')"
                    type="image"
                    :existingMedia="$desktopImage"
                />
            </div>
            <div class="col-md-6">
                <x-project-media-input
                    :name="$imagePrefix . '[mobile]'"
                    :label="__('custom.inputs.image_mobile')"
                    type="image"
                    :existingMedia="$mobileImage"
                />
            </div>
        </div>
    </div>
@endif

{{-- Videos (Desktop & Mobile) with Posters --}}
@if($needsVideo)
    <div class="col-12 mt-3">
        <div class="row">
            <div class="col-md-6">
                <x-project-media-input
                    :name="$videoPrefix . '[desktop]'"
                    :label="__('custom.inputs.video_desktop')"
                    type="video"
                    :existingMedia="$desktopVideo"
                />
            </div>
            <div class="col-md-6">
                <x-project-media-input
                    :name="$videoPrefix . '[mobile]'"
                    :label="__('custom.inputs.video_mobile')"
                    type="video"
                    :existingMedia="$mobileVideo"
                />
            </div>
            <div class="col-md-6">
                <x-project-media-input
                    :name="$videoPrefix . '[poster][desktop]'"
                    :label="__('custom.inputs.poster_desktop')"
                    type="image"
                    :existingMedia="$desktopPoster"
                />
            </div>
            <div class="col-md-6">
                <x-project-media-input
                    :name="$videoPrefix . '[poster][mobile]'"
                    :label="__('custom.inputs.poster_mobile')"
                    type="image"
                    :existingMedia="$mobilePoster"
                />
            </div>
        </div>
    </div>
@endif

{{-- Icon --}}
@if($needsIcon)
    <div class="col-12 mt-3">
        <x-project-media-input
            :name="$iconPrefix"
            :label="__('custom.inputs.icon')"
            type="icon"
            :existingMedia="$icon"
        />
    </div>
@endif

{{-- Gallery --}}
@if($needsGallery)
    <div class="col-12 mt-3">
        <x-project-media-input
            :name="$galleryPrefix . '[]'"
            :label="__('custom.inputs.gallery')"
            type="image"
            :existingMedia="$galleryItems"
            :multiple="true"
        />
    </div>
@endif

