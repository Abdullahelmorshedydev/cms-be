@props([
    'name' => '',
    'label' => '',
    'type' => 'image',
    'existingMedia' => null,
    'multiple' => false,
    'required' => false,
    'showHints' => true,
])

@php
    $id = 'media_' . str_replace(['[', ']', '.'], '_', $name);
    $previewId = $id . '_preview';
@endphp

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>

    <input
        type="file"
        class="form-control"
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $multiple ? 'multiple' : '' }}
        {{ $required ? 'required' : '' }}
        onchange="previewMediaFiles(this, '{{ $previewId }}')"
    >

    @if($showHints)
        <small class="form-text text-muted">
            @if($type === 'image')
                {{ __('custom.words.accepted') }}: JPG, PNG, WEBP | {{ __('custom.words.max') }}: 2MB
            @elseif($type === 'video')
                {{ __('custom.words.accepted') }}: MP4, WEBM | {{ __('custom.words.max') }}: 10MB
            @elseif($type === 'file')
                {{ __('custom.words.accepted') }}: PDF, DOC, XLS | {{ __('custom.words.max') }}: 5MB
            @elseif($type === 'icon')
                {{ __('custom.words.accepted') }}: SVG, PNG | {{ __('custom.words.max') }}: 1MB
            @endif
        </small>
    @endif

    <div id="{{ $previewId }}" class="mt-2 d-flex flex-wrap gap-2">
        {{-- Existing Media Display --}}
        @if($existingMedia)
            @php
                $mediaItems = is_iterable($existingMedia) && !($existingMedia instanceof \Illuminate\Database\Eloquent\Model)
                    ? $existingMedia
                    : [$existingMedia];
            @endphp

            @foreach($mediaItems as $media)
                @if($media)
                    <div class="position-relative d-inline-block media-existing-item">
                        @if(in_array($type, ['image', 'icon']))
                            <img src="{{ $media->url }}" class="img-thumbnail" style="max-width: 120px; max-height: 120px; object-fit: cover;">
                        @elseif($type === 'video')
                            <video src="{{ $media->url }}" controls style="max-width: 200px;" class="img-thumbnail"></video>
                        @else
                            <div class="border rounded p-2 bg-light">
                                <i class="fa fa-file me-1"></i>
                                <a href="{{ $media->url }}" target="_blank">{{ basename($media->name) }}</a>
                            </div>
                        @endif
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                style="width: 24px; height: 24px; padding: 0; border-radius: 50%; opacity: 0.8;"
                                onclick="removeExistingMedia(this, {{ $media->id }})"
                                title="{{ __('custom.words.delete') }}">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>

