@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.words.page'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.edit') . ' ' . __('custom.words.page') }}</h4>
                <a href="{{ route('dashboard.cms.pages.index') }}" class="btn btn-secondary">
                    {{ __('custom.words.back') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.cms.pages.update', $data['record']->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $data['record']->name) }}" required>
                                <label for="name">{{ __('custom.columns.name') }} *</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating form-floating-outline">
                                <select class="form-control @error('is_active') is-invalid @enderror" 
                                        id="is_active" name="is_active" required>
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['status'] as $stat)
                                        <option value="{{ $stat['value'] }}" 
                                                {{ old('is_active', $data['record']->is_active->value ?? '') == $stat['value'] ? 'selected' : '' }}>
                                            {{ $stat['lang'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="is_active">{{ __('custom.inputs.is_active') }} *</label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('custom.words.update') }}</button>
                            <a href="{{ route('dashboard.cms.pages.index') }}" class="btn btn-secondary">{{ __('custom.words.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


