<div class="row">
    <div class="col-md-12 mb-3">
        <div class="form-floating form-floating-outline">
            <input type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name', $sectionType->name ?? null) }}"
                   required>
            <label for="name">{{ __('custom.columns.name') }} *</label>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12 mb-3">
        <div class="form-floating form-floating-outline">
            <textarea class="form-control @error('description') is-invalid @enderror"
                      id="description"
                      name="description"
                      rows="3">{{ old('description', $sectionType->description ?? null) }}</textarea>
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
                    <input class="form-check-input"
                           type="checkbox"
                           name="fields[]"
                           value="{{ $field }}"
                           id="field_{{ $field }}"
                           {{ in_array($field, $currentFields, true) ? 'checked' : '' }}>
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
        @if(!empty($sectionType?->id) && $sectionType->getFirstMediaUrl('image'))
            <div class="mb-2">
                <img src="{{ $sectionType->getFirstMediaUrl('image') }}"
                     alt="Current image"
                     style="max-width: 200px; max-height: 200px;">
            </div>
        @endif
        <input type="file"
               class="form-control @error('image') is-invalid @enderror"
               id="image"
               name="image"
               accept="image/*">
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-primary">
            {{ $submitLabel ?? __('custom.words.save') }}
        </button>
        <a href="{{ route('dashboard.cms.section-types.index') }}" class="btn btn-secondary">
            {{ __('custom.words.cancel') }}
        </a>
    </div>
</div>


