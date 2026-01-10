@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.words.section'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.edit') . ' ' . __('custom.words.section') }}</h4>
                <a href="{{ route('dashboard.cms.sections.index') }}" class="btn btn-secondary">
                    {{ __('custom.words.back') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.cms.sections.update', $section->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    {{-- Container for removed media IDs (global for standalone section edit) --}}
                    <div id="removedMediaContainer"></div>
                    
                    @php($submitLabel = __('custom.words.update'))
                    @include('admin.pages.cms.sections._form', [
                        'section' => $section,
                        'sectionTypes' => $sectionTypes,
                        'pages' => $pages,
                        'page' => null,
                        'submitLabel' => $submitLabel,
                    ])
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
{{-- Universal Media Handler for file input previews (images, videos, icons, files) --}}
<script src="{{ asset('dashboard/assets/js/universal-media-handler.js') }}"></script>
{{-- Media Service for FormData handling in API calls --}}
<script src="{{ asset('dashboard/assets/js/services/mediaService.js') }}"></script>
@endsection


