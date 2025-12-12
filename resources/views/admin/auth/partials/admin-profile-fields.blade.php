{{-- Admin Profile Fields --}}
<div class="col-md-6 mt-3">
    <div class="form-group">
        <label for="genderInput">{{ __('custom.inputs.gender') }}</label>
        <select id="genderInput" class="form-select" name="gender">
            <option value="">{{ __('custom.words.select') }}</option>
            <option value="1" {{ old('gender', $user->gender?->value) == 1 ? 'selected' : '' }}>
                {{ __('custom.enums.male') }}
            </option>
            <option value="2" {{ old('gender', $user->gender?->value) == 2 ? 'selected' : '' }}>
                {{ __('custom.enums.female') }}
            </option>
        </select>
        @error('gender')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="col-md-6 mt-3">
    <div class="form-group">
        <label for="date_of_birthInput">{{ __('custom.inputs.date_of_birth') }}</label>
        <input id="date_of_birthInput" class="form-control" type="date" name="date_of_birth"
            value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
        @error('date_of_birth')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="col-md-6 mt-3">
    <div class="form-group">
        <label for="bioInput">{{ __('custom.inputs.bio') }}</label>
        <textarea id="bioInput" class="form-control" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
        @error('bio')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="col-md-6 mt-3">
    <div class="form-group">
        <label for="job_titleInput">{{ __('custom.inputs.job_title') }}</label>
        <input id="job_titleInput" class="form-control" type="text" name="job_title"
            value="{{ old('job_title', $user->job_title) }}">
        @error('job_title')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Address Fields --}}
<div class="col-12 mt-3">
    <h5>{{ __('custom.words.address_details') }}</h5>
</div>

<div class="col-md-6 mt-3">
    <div class="form-group">
        <label for="address_streetInput">{{ __('custom.inputs.street') }}</label>
        <input id="address_streetInput" class="form-control" type="text" name="address[street]"
            value="{{ old('address.street', $user->address['street'] ?? '') }}">
        @error('address.street')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="col-md-6 mt-3">
    <div class="form-group">
        <label for="address_cityInput">{{ __('custom.inputs.city') }}</label>
        <input id="address_cityInput" class="form-control" type="text" name="address[city]"
            value="{{ old('address.city', $user->address['city'] ?? '') }}">
        @error('address.city')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="col-md-6 mt-3">
    <div class="form-group">
        <label for="address_stateInput">{{ __('custom.inputs.state') }}</label>
        <input id="address_stateInput" class="form-control" type="text" name="address[state]"
            value="{{ old('address.state', $user->address['state'] ?? '') }}">
        @error('address.state')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="col-md-6 mt-3">
    <div class="form-group">
        <label for="address_countryInput">{{ __('custom.inputs.country') }}</label>
        <input id="address_countryInput" class="form-control" type="text" name="address[country]"
            value="{{ old('address.country', $user->address['country'] ?? '') }}">
        @error('address.country')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="col-md-6 mt-3">
    <div class="form-group">
        <label for="address_postal_codeInput">{{ __('custom.inputs.postal_code') }}</label>
        <input id="address_postal_codeInput" class="form-control" type="text" name="address[postal_code]"
            value="{{ old('address.postal_code', $user->address['postal_code'] ?? '') }}">
        @error('address.postal_code')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

