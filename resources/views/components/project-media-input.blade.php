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
        @if($type === 'image' || $type === 'icon')
            accept="image/*"
        @elseif($type === 'video')
            accept="video/*"
        @elseif($type === 'file')
            accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.7z"
        @endif
        onchange="if(window.previewMediaFiles) window.previewMediaFiles(this, '{{ $previewId }}')"
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
                    @php
                        $mediaUrl = $media->url ?? (url('storage/' . $media->media_path . '/' . $media->name));
                        $mediaType = $media->type ?? $type;
                        $fileName = basename($media->name ?? '');
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        
                        // Determine icon based on file type
                        $iconClass = 'mdi-file-document';
                        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                            $iconClass = 'mdi-image';
                        } elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg', 'mov', 'avi'])) {
                            $iconClass = 'mdi-video';
                        } elseif ($fileExtension === 'pdf') {
                            $iconClass = 'mdi-file-pdf-box';
                        } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                            $iconClass = 'mdi-file-word-box';
                        } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
                            $iconClass = 'mdi-file-excel-box';
                        } elseif (in_array($fileExtension, ['zip', 'rar', '7z'])) {
                            $iconClass = 'mdi-folder-zip';
                        }
                    @endphp
                    <div class="position-relative d-inline-block media-existing-item" style="transition: transform 0.2s;">
                        @if(in_array($type, ['image', 'icon']))
                            <img src="{{ $mediaUrl }}" 
                                 class="img-thumbnail media-preview-thumbnail" 
                                 style="max-width: 150px; max-height: 150px; object-fit: cover; cursor: pointer; border: 2px solid transparent; transition: all 0.2s;"
                                 onclick="if(window.showMediaPreview) window.showMediaPreview('{{ $mediaUrl }}', '{{ $type }}', '{{ addslashes($fileName) }}')"
                                 onmouseover="this.style.transform='scale(1.05)'; this.style.borderColor='#0d6efd'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.15)';"
                                 onmouseout="this.style.transform='scale(1)'; this.style.borderColor='transparent'; this.style.boxShadow='none';"
                                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 loading="lazy"
                                 alt="{{ $fileName }}">
                            <div class="d-none align-items-center justify-content-center bg-light rounded position-absolute top-0 start-0" 
                                 style="width: 150px; height: 150px;">
                                <i class="mdi mdi-{{ $iconClass }} mdi-48px text-muted"></i>
                            </div>
                        @elseif($type === 'video')
                            <div style="position: relative; max-width: 200px;">
                                <video src="{{ $mediaUrl }}" 
                                       style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 4px; cursor: pointer;"
                                       class="img-thumbnail"
                                       onclick="if(window.showMediaPreview) window.showMediaPreview('{{ $mediaUrl }}', 'video', '{{ addslashes($fileName) }}')"
                                       onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.15)';"
                                       onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';"
                                       muted
                                       preload="metadata">
                                </video>
                                <div class="position-absolute top-50 start-50 translate-middle" 
                                     style="background: rgba(0,0,0,0.7); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; pointer-events: none;">
                                    <i class="mdi mdi-play text-white"></i>
                                </div>
                            </div>
                        @else
                            <div class="border rounded p-3 bg-light text-center media-file-preview" 
                                 style="min-width: 120px; cursor: pointer; transition: all 0.2s;"
                                 onclick="if(window.showMediaPreview) window.showMediaPreview('{{ $mediaUrl }}', 'file', '{{ addslashes($fileName) }}')"
                                 onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.15)';"
                                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                <i class="mdi {{ $iconClass }} mdi-48px text-primary mb-2 d-block"></i>
                                <small class="text-muted d-block" style="word-break: break-word; max-width: 120px;">{{ $fileName }}</small>
                            </div>
                        @endif
                        <button type="button" 
                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                style="width: 28px; height: 28px; padding: 0; border-radius: 50%; opacity: 0.9; z-index: 10;"
                                onclick="removeExistingMedia(this, {{ $media->id }}, '{{ $name }}')"
                                title="{{ __('custom.words.delete') }}">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>

