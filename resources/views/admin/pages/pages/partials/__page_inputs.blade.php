<x-input-name :record="$data['record'] ?? null" :locales="$data['locales']" :isSection="false" />

<div class="col-md-12 mt-3">
    <div class="form-floating form-floating-outline">
        <select id="statusSelect" class="form-control" name="is_active">
            <option value="">{{ __('custom.words.choose') }}</option>
            @foreach ($data['status'] as $stat)
                <option value="{{ $stat['value'] }}"
                    {{ old('is_active', isset($data['record']) ? $data['record']->is_active->value : '') == $stat['value'] ? 'selected' : '' }}>
                    {{ $stat['lang'] }}
                </option>
            @endforeach
        </select>
        <label for="statusSelect">{{ __('custom.inputs.is_active') }}</label>
        @error('is_active')
            <div class="text-danger"> {{ $message }}</div>
        @enderror
    </div>
</div>
