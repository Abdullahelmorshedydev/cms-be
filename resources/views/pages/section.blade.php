<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $section->name }} - {{ $page->name }} - {{ config('app.name', 'Laravel') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <nav class="mb-6">
            <a href="{{ route('pages.show', $page->slug) }}" class="text-blue-600 hover:text-blue-800">← Back to {{ $page->name }}</a>
        </nav>

        <article class="bg-white rounded-lg shadow-md p-8">
            <header class="mb-6">
                <h1 class="text-3xl font-bold mb-2">{{ $section->name }}</h1>
                <p class="text-gray-600">Section from: <a href="{{ route('pages.show', $page->slug) }}" class="text-blue-600 hover:text-blue-800">{{ $page->name }}</a></p>
            </header>

            @if($section->content)
                <div class="prose max-w-none mb-6">
                    @php
                        $content = is_string($section->content) ? json_decode($section->content, true) : $section->content;
                    @endphp
                    @if(is_array($content))
                        @if(isset($content['title']))
                            <h2 class="text-2xl font-semibold mb-3">{{ is_array($content['title']) ? $content['title'][app()->getLocale()] ?? reset($content['title']) : $content['title'] }}</h2>
                        @endif
                        @if(isset($content['subtitle']))
                            <h3 class="text-xl font-medium mb-3">{{ is_array($content['subtitle']) ? $content['subtitle'][app()->getLocale()] ?? reset($content['subtitle']) : $content['subtitle'] }}</h3>
                        @endif
                        @if(isset($content['description']))
                            <p class="mb-3">{{ is_array($content['description']) ? $content['description'][app()->getLocale()] ?? reset($content['description']) : $content['description'] }}</p>
                        @endif
                        @if(isset($content['short_description']))
                            <p class="text-gray-600 mb-3">{{ is_array($content['short_description']) ? $content['short_description'][app()->getLocale()] ?? reset($content['short_description']) : $content['short_description'] }}</p>
                        @endif
                    @else
                        {!! nl2br(e($section->content)) !!}
                    @endif
                </div>
            @endif

            @if($section->button_text && $section->button_data)
                <div class="mb-6">
                    <a href="{{ $section->button_data }}" 
                       class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        {{ $section->button_text }}
                    </a>
                </div>
            @endif

            @if(isset($section->media) && $section->media->count() > 0)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Media</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($section->media as $media)
                            @php
                                $mediaUrl = is_array($media) ? ($media['url'] ?? '') : ($media->url ?? '');
                                $mediaName = is_array($media) ? ($media['name'] ?? 'Image') : ($media->name ?? 'Image');
                                $mimeType = is_array($media) ? ($media['mime_type'] ?? '') : ($media->mime_type ?? '');
                            @endphp
                            @if($mediaUrl)
                                @if($mimeType && str_starts_with($mimeType, 'image/'))
                                    <div class="rounded-lg overflow-hidden">
                                        <img src="{{ $mediaUrl }}" alt="{{ $mediaName }}" class="w-full h-auto">
                                    </div>
                                @else
                                    <div class="border rounded-lg p-4">
                                        <a href="{{ $mediaUrl }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            {{ $mediaName }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @if(isset($section->sub_sections) && $section->sub_sections->count() > 0)
                <div class="mt-8 pt-6 border-t">
                    <h2 class="text-xl font-semibold mb-4">Sub-sections</h2>
                    <div class="space-y-4">
                        @foreach($section->sub_sections as $subSection)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold mb-2">{{ $subSection->name }}</h3>
                                @if($subSection->content)
                                    <p class="text-gray-700">{{ $subSection->content }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-8 pt-6 border-t">
                <h2 class="text-lg font-semibold mb-2">Section Details</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-600">Order</dt>
                        <dd class="text-gray-900">{{ $section->order ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-600">Type</dt>
                        <dd class="text-gray-900">{{ $section->type ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-600">Button Type</dt>
                        <dd class="text-gray-900">{{ $section->button_type ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-600">Status</dt>
                        <dd class="text-gray-900">{{ isset($section->disabled) && $section->disabled ? 'Disabled' : 'Active' }}</dd>
                    </div>
                </dl>
            </div>
        </article>
    </div>
</body>
</html>

