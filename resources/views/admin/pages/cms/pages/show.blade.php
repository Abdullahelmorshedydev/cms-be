@extends('admin.layouts.app')

@section('title', __('custom.words.show') . ' ' . __('custom.words.page'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.show') . ' ' . __('custom.words.page') }}</h4>
                <div>
                    <a href="{{ route('dashboard.cms.pages.edit', $page->id) }}" class="btn btn-warning">
                        {{ __('custom.words.edit') }}
                    </a>
                    <a href="{{ route('dashboard.cms.pages.index') }}" class="btn btn-secondary">
                        {{ __('custom.words.back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.name') }}:</strong>
                        <p>{{ $page->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.slug') }}:</strong>
                        <p>{{ $page->slug }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.activation') }}:</strong>
                        <p>
                            <span class="badge bg-{{ $page->is_active ? 'success' : 'danger' }}">
                                {{ $page->is_active ? __('custom.words.active') : __('custom.words.inactive') }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.created_at') }}:</strong>
                        <p>{{ $page->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    @if($sections && $sections->count() > 0)
                        <div class="col-md-12 mb-3">
                            <strong>{{ __('custom.words.sections') }}:</strong>
                            <ul class="list-group mt-2">
                                @foreach($sections as $section)
                                    <li class="list-group-item">
                                        {{ $section->name }} - {{ $section->type }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

