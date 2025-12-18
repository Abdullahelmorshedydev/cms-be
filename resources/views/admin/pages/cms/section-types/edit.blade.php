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
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $sectionType->name) }}" required>
                                <label for="name">{{ __('custom.columns.name') }} *</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description', $sectionType->description) }}</textarea>
                                <label for="description">{{ __('custom.columns.description') }}</label>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('custom.columns.fields') }} *</label>
                            <div class="form-check-group">
                                @php
                                    $fieldOptions = \App\Enums\SectionFieldEnum::values();
                                    $currentFields = old('fields', $sectionType->fields ?? []);
                                @endphp
                                @foreach($fieldOptions as $field)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" 
                                               value="{{ $field }}" id="field_{{ $field }}"
                                               {{ in_array($field, $currentFields) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="field_{{ $field }}">
                                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('fields')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('custom.columns.image') }}</label>
                            @if($sectionType->getFirstMediaUrl('image'))
                                <div class="mb-2">
                                    <img src="{{ $sectionType->getFirstMediaUrl('image') }}" alt="Current image" style="max-width: 200px; max-height: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('custom.words.update') }}</button>
                            <a href="{{ route('dashboard.cms.section-types.index') }}" class="btn btn-secondary">{{ __('custom.words.cancel') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

