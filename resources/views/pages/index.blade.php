<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pages - {{ config('app.name', 'Laravel') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">All Pages</h1>
        
        @if($pages && $pages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pages as $page)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="{{ route('pages.show', $page->slug ?? $page->name) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $page->name ?? 'Unnamed Page' }}
                            </a>
                        </h2>
                        @if(isset($page->sections) && $page->sections->count() > 0)
                            <p class="text-gray-600 text-sm">
                                {{ $page->sections->count() }} section(s)
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-600">No pages found.</p>
            </div>
        @endif
    </div>
</body>
</html>

