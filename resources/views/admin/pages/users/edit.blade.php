@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.user.user'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.create_new') . ' ' . __('custom.user.user') }}</h4>
                <a href="{{ route('dashboard.users.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.users.update', $data['record']->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="nameInput" class="form-control" type="text" name="name"
                                    value="{{ old('name', $data['record']->name) }}">
                                <label for="nameInput">{{ __('custom.inputs.name') }}</label>
                                @error('name')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="emailInput" class="form-control" type="email" name="email"
                                    value="{{ old('email', $data['record']->email) }}">
                                <label for="emailInput">{{ __('custom.inputs.email') }}</label>
                                @error('email')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="passwordInput" class="form-control" type="password" name="password"
                                    value="{{ old('password') }}">
                                <label for="passwordInput">{{ __('custom.inputs.password') }}</label>
                                @error('password')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="passwordInput" class="form-control" type="password" name="password_confirmation"
                                    value="{{ old('password_confirmation') }}">
                                <label for="passwordInput">{{ __('custom.inputs.password_confirmation') }}</label>
                                @error('password_confirmation')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline" style="position: relative">
                                <select name="country_code" class="form-select country-code-select"
                                    style="max-width: 140px; position: absolute; top: 0; right: 0">
                                </select>
                                <input id="phone" class="form-control" type="text" name="phone"
                                    value="{{ old('phone', $data['record']->phone) }}">
                                <label for="phone">{{ __('custom.inputs.phone') }}</label>
                                @error('phone')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="addressCountry" class="form-control" type="text" name="address[country]"
                                    value="{{ old('address.country', $data['record']->address['country']) }}">
                                <label for="addressCountry">{{ __('custom.inputs.country') }}</label>
                                @error('address.country')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="addressCity" class="form-control" type="text" name="address[city]"
                                    value="{{ old('address.city', $data['record']->address['city']) }}">
                                <label for="addressCity">{{ __('custom.inputs.city') }}</label>
                                @error('address.city')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="addressStreet" class="form-control" type="text" name="address[street]"
                                    value="{{ old('address.street', $data['record']->address['street']) }}">
                                <label for="addressStreet">{{ __('custom.inputs.street') }}</label>
                                @error('address.street')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select id="is_adminSelect" class="form-control" name="is_admin">
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    <option value="1"
                                        {{ old('is_admin', $data['record']->is_admin) == 1 ? 'selected' : '' }}>
                                        {{ __('custom.user.admin') }}</option>
                                    <option value="0"
                                        {{ old('is_admin', $data['record']->is_admin) == 0 ? 'selected' : '' }}>
                                        {{ __('custom.user.customer') }}</option>
                                </select>
                                <label for="is_adminSelect">{{ __('custom.inputs.is_admin') }}</label>
                                @error('is_admin')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select id="roleSelect" class="form-control" name="role">
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['roles'] as $role)
                                        <option value="{{ $role->name }}"
                                            {{ old('role', $data['record']->getRoleNames()->count() ? $data['record']->getRoleNames()[0] : '') == $role->name ? 'selected' : '' }}>
                                            {{ json_decode($role->display_name)->{LaravelLocalization::getCurrentLocale()} }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="roleSelect">{{ __('custom.inputs.role') }}</label>
                                @error('role')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-floating form-floating-outline">
                                <button type="submit" class="btn btn-primary">{{ __('custom.words.update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const countryCodes = [{
                code: "+20",
                name: "Egypt"
            },
            {
                code: "+1",
                name: "United States"
            },
            {
                code: "+44",
                name: "United Kingdom"
            },
            {
                code: "+61",
                name: "Australia"
            },
            {
                code: "+91",
                name: "India"
            },
            {
                code: "+49",
                name: "Germany"
            },
            {
                code: "+81",
                name: "Japan"
            },
            {
                code: "+33",
                name: "France"
            },
            {
                code: "+34",
                name: "Spain"
            },
            {
                code: "+86",
                name: "China"
            },
            {
                code: "+39",
                name: "Italy"
            },
            {
                code: "+7",
                name: "Russia"
            },
            {
                code: "+55",
                name: "Brazil"
            },
            {
                code: "+1-242",
                name: "Bahamas"
            },
            {
                code: "+1-246",
                name: "Barbados"
            },
            {
                code: "+1-441",
                name: "Bermuda"
            },
            {
                code: "+1-268",
                name: "Antigua and Barbuda"
            },
            // Add more country codes as needed
        ];

        let phoneIndex = 1;

        function populateCountryCodes(selectElement) {
            countryCodes.forEach(country => {
                const option = document.createElement('option');
                option.value = country.code;
                option.textContent = `${country.name} (${country.code})`;
                selectElement.appendChild(option);
            });
        }

        populateCountryCodes(document.querySelector('.country-code-select'));
    </script>
@endsection
