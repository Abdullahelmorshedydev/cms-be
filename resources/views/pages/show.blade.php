<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->name }} - {{ config('app.name', 'Laravel') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <nav class="mb-6">
            <a href="{{ route('pages.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Pages</a>
        </nav>

        <header class="mb-8">
            <h1 class="text-4xl font-bold mb-2">{{ $page->name }}</h1>
            @if($page->slug)
                <p class="text-gray-600">Slug: {{ $page->slug }}</p>
            @endif
        </header>

        @if($sections && $sections->count() > 0)
            <div class="space-y-8">
                @foreach($sections as $section)
                    <article class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-semibold mb-4">{{ $section->name }}</h2>
                        
                        @if($section->content)
                            <div class="prose max-w-none mb-4">
                                @php
                                    $content = is_string($section->content) ? json_decode($section->content, true) : $section->content;
                                @endphp
                                @if(is_array($content))
                                    @if(isset($content['title']))
                                        <h3 class="text-xl font-semibold mb-2">{{ is_array($content['title']) ? $content['title'][app()->getLocale()] ?? reset($content['title']) : $content['title'] }}</h3>
                                    @endif
                                    @if(isset($content['subtitle']))
                                        <h4 class="text-lg font-medium mb-2">{{ is_array($content['subtitle']) ? $content['subtitle'][app()->getLocale()] ?? reset($content['subtitle']) : $content['subtitle'] }}</h4>
                                    @endif
                                    @if(isset($content['description']))
                                        <p class="mb-2">{{ is_array($content['description']) ? $content['description'][app()->getLocale()] ?? reset($content['description']) : $content['description'] }}</p>
                                    @endif
                                    @if(isset($content['short_description']))
                                        <p class="text-gray-600 mb-2">{{ is_array($content['short_description']) ? $content['short_description'][app()->getLocale()] ?? reset($content['short_description']) : $content['short_description'] }}</p>
                                    @endif
                                @else
                                    {!! nl2br(e($section->content)) !!}
                                @endif
                            </div>
                        @endif

                        @if($section->button_text && $section->button_data)
                            <div class="mt-4">
                                <a href="{{ $section->button_data }}" 
                                   class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                    {{ $section->button_text }}
                                </a>
                            </div>
                        @endif

                        @if(isset($section->media) && $section->media->count() > 0)
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($section->media as $media)
                                    @php
                                        $mediaUrl = is_array($media) ? ($media['url'] ?? '') : ($media->url ?? '');
                                        $mediaName = is_array($media) ? ($media['name'] ?? 'Image') : ($media->name ?? 'Image');
                                    @endphp
                                    @if($mediaUrl)
                                        <img src="{{ $mediaUrl }}" alt="{{ $mediaName }}" class="rounded-lg w-full h-auto">
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @if(isset($section->sub_sections) && $section->sub_sections->count() > 0)
                            <div class="mt-6 pl-6 border-l-4 border-gray-300">
                                <h3 class="text-lg font-semibold mb-3">Sub-sections</h3>
                                @foreach($section->sub_sections as $subSection)
                                    <div class="mb-4">
                                        <h4 class="font-medium">{{ $subSection->name }}</h4>
                                        @if($subSection->content)
                                            <p class="text-gray-700 mt-2">{{ $subSection->content }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('pages.section', [$page->slug, $section->name]) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                View section details →
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-600">No sections found for this page.</p>
            </div>
        @endif
    </div>
</body>
</html>

