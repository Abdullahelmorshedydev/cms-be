<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-floating form-floating-outline">
            <input type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name', $section->name ?? null) }}"
                   required>
            <label for="name">{{ __('custom.columns.name') }} *</label>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-floating form-floating-outline">
            <select class="form-control @error('type') is-invalid @enderror"
                    id="type"
                    name="type"
                    required>
                <option value="">{{ __('custom.words.choose') }}</option>
                @foreach($sectionTypes as $sectionType)
                    <option value="{{ $sectionType->slug }}"
                        {{ old('type', $section->type ?? null) == $sectionType->slug ? 'selected' : '' }}>
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

    @if(isset($page) && $page)
        <input type="hidden" name="page_id" value="{{ $page->id }}">
        <input type="hidden" name="parent_type" value="page">
    @else
        <div class="col-md-6 mb-3">
            <div class="form-floating form-floating-outline">
                <select class="form-control @error('page_id') is-invalid @enderror"
                        id="page_id"
                        name="page_id">
                    <option value="">{{ __('custom.words.choose') }}</option>
                    @foreach($pages as $p)
                        <option value="{{ $p->id }}"
                            {{ (int) old('page_id', $section->parent_id ?? null) === (int) $p->id ? 'selected' : '' }}>
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
            <input type="number"
                   class="form-control @error('order') is-invalid @enderror"
                   id="order"
                   name="order"
                   value="{{ old('order', $section->order ?? null) }}">
            <label for="order">{{ __('custom.columns.order') }}</label>
            @error('order')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-primary">
            {{ $submitLabel ?? __('custom.words.save') }}
        </button>
        <a href="{{ route('dashboard.cms.sections.index') }}" class="btn btn-secondary">
            {{ __('custom.words.cancel') }}
        </a>
    </div>
</div>


