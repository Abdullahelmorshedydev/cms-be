@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

<div class="row">
    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <div class="col-md-6 mb-3">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control @error('name.' . $localeCode) is-invalid @enderror"
                    id="name_{{ $localeCode }}" name="name[{{ $localeCode }}]"
                    value="{{ old('name.' . $localeCode, $page?->getTranslation('name', $localeCode)) }}">
                <label for="name_{{ $localeCode }}">{{ __('custom.inputs.name_' . $localeCode) }}</label>
                @error('name.' . $localeCode)
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    @endforeach

    <div class="col-md-12 mb-3">
        <div class="form-floating form-floating-outline">
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="">{{ __('custom.words.choose') }}</option>
                @foreach ($data['status'] as $stat)
                    <option value="{{ $stat['value'] }}" {{ old('status', $page->status->value ?? '') == $stat['value'] ? 'selected' : '' }}>
                        {{ $stat['lang'] }}
                    </option>
                @endforeach
            </select>
            <label for="status">{{ __('custom.inputs.status') }}</label>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-primary">
            {{ $submitLabel ?? __('custom.words.save') }}
        </button>
    </div>
</div>
