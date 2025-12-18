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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                <label for="name">{{ __('custom.columns.name') }} *</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach($sectionTypes as $sectionType)
                                        <option value="{{ $sectionType->slug }}" {{ old('type') == $sectionType->slug ? 'selected' : '' }}>
                                            {{ $sectionType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="type">{{ __('custom.columns.type') }} *</label>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @if($page)
                            <input type="hidden" name="page_id" value="{{ $page->id }}">
                            <input type="hidden" name="parent_type" value="page">
                        @else
                            <div class="col-md-6 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-control @error('page_id') is-invalid @enderror" 
                                            id="page_id" name="page_id">
                                        <option value="">{{ __('custom.words.choose') }}</option>
                                        @foreach($pages as $p)
                                            <option value="{{ $p->id }}" {{ old('page_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="page_id">{{ __('custom.words.page') }}</label>
                                    @error('page_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="order" name="order" value="{{ old('order') }}">
                                <label for="order">{{ __('custom.columns.order') }}</label>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('custom.words.create') }}</button>
                            <a href="{{ route('dashboard.cms.sections.index') }}" class="btn btn-secondary">{{ __('custom.words.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


