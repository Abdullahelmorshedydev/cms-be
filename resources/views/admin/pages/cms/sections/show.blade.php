@extends('admin.layouts.app')

@section('title', __('custom.words.show') . ' ' . __('custom.words.section'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.show') . ' ' . __('custom.words.section') }}</h4>
                <div>
                    <a href="{{ route('dashboard.cms.sections.edit', $section->id) }}" class="btn btn-warning">
                        {{ __('custom.words.edit') }}
                    </a>
                    <a href="{{ route('dashboard.cms.sections.index') }}" class="btn btn-secondary">
                        {{ __('custom.words.back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.name') }}:</strong>
                        <p>{{ $section->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.type') }}:</strong>
                        <p>{{ $section->type }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.parent') }}:</strong>
                        <p>
                            @if($section->parent_type)
                                {{ class_basename($section->parent_type) }} #{{ $section->parent_id }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.order') }}:</strong>
                        <p>{{ $section->order ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.created_at') }}:</strong>
                        <p>{{ $section->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    @if($section->content)
                        <div class="col-md-12 mb-3">
                            <strong>{{ __('custom.words.content') }}:</strong>
                            <div class="p-3 bg-light rounded">
                                <pre>{{ json_encode($section->content, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


