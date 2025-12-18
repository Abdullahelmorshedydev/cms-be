@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.words.section_type'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.edit') . ' ' . __('custom.words.section_type') }}</h4>
                <a href="{{ route('dashboard.cms.section-types.index') }}" class="btn btn-secondary">
                    {{ __('custom.words.back') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.cms.section-types.update', $sectionType->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @php($submitLabel = __('custom.words.update'))
                    @include('admin.pages.cms.section-types._form', compact('sectionType', 'submitLabel'))
                </form>
            </div>
        </div>
    </div>
@endsection

