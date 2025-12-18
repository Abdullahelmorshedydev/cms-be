@extends('admin.layouts.app')

@section('title', __('custom.words.create_new') . ' ' . __('custom.words.section'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.create_new') . ' ' . __('custom.words.section') }}</h4>
                <a href="{{ route('dashboard.cms.sections.index') }}" class="btn btn-secondary">
                    {{ __('custom.words.back') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.cms.sections.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @php($section = null)
                    @php($submitLabel = __('custom.words.create'))
                    @include('admin.pages.cms.sections._form', compact('section', 'sectionTypes', 'pages', 'page', 'submitLabel'))
                </form>
            </div>
        </div>
    </div>
@endsection


