@extends('admin.layouts.app')

@section('title', __('custom.words.show') . ' ' . __('custom.words.section_type'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.show') . ' ' . __('custom.words.section_type') }}</h4>
                <div>
                    <a href="{{ route('dashboard.cms.section-types.edit', $sectionType->id) }}" class="btn btn-warning">
                        {{ __('custom.words.edit') }}
                    </a>
                    <a href="{{ route('dashboard.cms.section-types.index') }}" class="btn btn-secondary">
                        {{ __('custom.words.back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.name') }}:</strong>
                        <p>{{ $sectionType->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.slug') }}:</strong>
                        <p>{{ $sectionType->slug }}</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <strong>{{ __('custom.columns.description') }}:</strong>
                        <p>{{ $sectionType->description ?? '-' }}</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <strong>{{ __('custom.columns.fields') }}:</strong>
                        <p>
                            @if($sectionType->fields && count($sectionType->fields) > 0)
                                @foreach($sectionType->fields as $field)
                                    <span class="badge bg-info me-1">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    @if($sectionType->getFirstMediaUrl('image'))
                        <div class="col-md-12 mb-3">
                            <strong>{{ __('custom.columns.image') }}:</strong>
                            <div class="mt-2">
                                <img src="{{ $sectionType->getFirstMediaUrl('image') }}" alt="Section type image" style="max-width: 300px; max-height: 300px;">
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.created_at') }}:</strong>
                        <p>{{ $sectionType->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>{{ __('custom.columns.updated_at') }}:</strong>
                        <p>{{ $sectionType->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


