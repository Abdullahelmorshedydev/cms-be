@extends('admin.layouts.app')

@section('title', __('custom.words.create_new') . ' ' . __('custom.project.project'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- projects List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.create_new') . ' ' . __('custom.project.project') }}</h4>
                <a href="{{ route('dashboard.projects.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.projects.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="nameEnInput" class="form-control" type="text" name="name[en]"
                                    value="{{ old('name.en') }}">
                                <label for="nameEnInput">{{ __('custom.inputs.name_en') }}</label>
                                @error('name.en')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="nameArInput" class="form-control" type="text" name="name[ar]"
                                    value="{{ old('name.ar') }}">
                                <label for="nameArInput">{{ __('custom.inputs.name_ar') }}</label>
                                @error('name.ar')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="urlInput" class="form-control" type="text" name="url"
                                    value="{{ old('url') }}">
                                <label for="urlInput">{{ __('custom.inputs.url') }}</label>
                                @error('url')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="short_descriptionEnInput" class="form-control" type="text" name="short_description[en]"
                                    value="{{ old('short_description.en') }}">
                                <label for="short_descriptionEnInput">{{ __('custom.inputs.short_description_en') }}</label>
                                @error('short_description.en')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="short_descriptionArInput" class="form-control" type="text" name="short_description[ar]"
                                    value="{{ old('short_description.ar') }}">
                                <label for="short_descriptionArInput">{{ __('custom.inputs.short_description_ar') }}</label>
                                @error('short_description.ar')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <textarea name="description[en]" id="descriptionEnInput" class="form-control">{!! old('description.en') !!}</textarea>
                                <label for="descriptionEnInput">{{ __('custom.inputs.description_en') }}</label>
                                @error('description.en')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <textarea name="description[ar]" id="descriptionArInput" class="form-control">{!! old('description.ar') !!}</textarea>
                                <label for="descriptionArInput">{{ __('custom.inputs.description_ar') }}</label>
                                @error('description.ar')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select id="statusSelect" class="form-control" name="status">
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['status'] as $status)
                                        <option value="{{ $status['value'] }}" {{ old('status') == $status['value'] ? 'selected' : '' }}>
                                            {{ $status['lang'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="statusSelect">{{ __('custom.inputs.status') }}</label>
                                @error('status')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="nameArInput" class="form-control" type="file" name="image">
                                <label for="statusSelect">{{ __('custom.inputs.image') }}</label>
                                @error('image')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-floating form-floating-outline">
                                <button type="submit" class="btn btn-primary">{{ __('custom.words.create') }}</button>
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
