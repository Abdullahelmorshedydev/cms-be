@extends('dashboard.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.student.student'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Students List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.edit') . ' ' . __('custom.student.student') }}</h4>
                <a href="{{ route('dashboard.students.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.students.update', $data['record']->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        {{-- Basic Information --}}
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('custom.student.student_info') }}</h5>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="nameInput" class="form-control" type="text" name="name"
                                    value="{{ old('name', $data['record']->name) }}" required>
                                <label for="nameInput">{{ __('custom.inputs.name') }} <span class="text-danger">*</span></label>
                                @error('name')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="emailInput" class="form-control" type="email" name="email"
                                    value="{{ old('email', $data['record']->email) }}" required>
                                <label for="emailInput">{{ __('custom.inputs.email') }} <span class="text-danger">*</span></label>
                                @error('email')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="passwordInput" class="form-control" type="password" name="password">
                                <label for="passwordInput">{{ __('custom.inputs.password') }}</label>
                                <small class="text-muted">{{ __('Leave blank to keep current password') }}</small>
                                @error('password')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="passwordConfirmationInput" class="form-control" type="password" name="password_confirmation">
                                <label for="passwordConfirmationInput">{{ __('custom.inputs.password_confirmation') }}</label>
                                @error('password_confirmation')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline" style="position: relative">
                                <select name="country_code" class="form-select country-code-select"
                                    style="max-width: 140px; position: absolute; top: 0; right: 0" required>
                                </select>
                                <input id="phone" class="form-control" type="text" name="phone"
                                    value="{{ old('phone', $data['record']->phone) }}" required>
                                <label for="phone">{{ __('custom.inputs.phone') }} <span class="text-danger">*</span></label>
                                @error('phone')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select id="genderSelect" class="form-control" name="gender">
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['genders'] as $gender)
                                        <option value="{{ $gender['value'] }}"
                                            {{ old('gender', $data['record']?->gender?->value) == $gender['value'] ? 'selected' : '' }}>
                                            {{ $gender['lang'] }}</option>
                                    @endforeach
                                </select>
                                <label for="genderSelect">{{ __('custom.inputs.gender') }}</label>
                                @error('gender')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="date_of_birthInput" class="form-control" type="date" name="date_of_birth"
                                    value="{{ old('date_of_birth', $data['record']->date_of_birth?->format('Y-m-d')) }}">
                                <label for="date_of_birthInput">{{ __('custom.inputs.date_of_birth') }}</label>
                                @error('date_of_birth')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Academic Information --}}
                        <div class="col-12 mt-4">
                            <h5 class="mb-3">{{ __('custom.student.academic_info') }}</h5>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="studentIdInput" class="form-control" type="text" name="student_id"
                                    value="{{ old('student_id', $data['record']->student_id) }}">
                                <label for="studentIdInput">{{ __('custom.inputs.student_id') }}</label>
                                @error('student_id')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="gradeInput" class="form-control" type="text" name="grade"
                                    value="{{ old('grade', $data['record']->grade) }}">
                                <label for="gradeInput">{{ __('custom.inputs.grade') }}</label>
                                @error('grade')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="classInput" class="form-control" type="text" name="class"
                                    value="{{ old('class', $data['record']->class) }}">
                                <label for="classInput">{{ __('custom.inputs.class') }}</label>
                                @error('class')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="academicYearInput" class="form-control" type="text" name="academic_year"
                                    value="{{ old('academic_year', $data['record']->academic_year) }}" placeholder="2023-2024">
                                <label for="academicYearInput">{{ __('custom.inputs.academic_year') }}</label>
                                @error('academic_year')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select id="parentSelect" class="form-control" name="parent_id">
                                    <option value="">{{ __('custom.student.select_parent') }}</option>
                                    @foreach ($data['parents'] as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ old('parent_id', $data['record']->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }} ({{ $parent->email }})</option>
                                    @endforeach
                                </select>
                                <label for="parentSelect">{{ __('custom.inputs.parent_name') }}</label>
                                @error('parent_id')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select id="statusSelect" class="form-control" name="is_active" required>
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['status'] as $stat)
                                        <option value="{{ $stat['value'] }}"
                                            {{ old('is_active', $data['record']->is_active->value) == $stat['value'] ? 'selected' : '' }}>
                                            {{ $stat['lang'] }}</option>
                                    @endforeach
                                </select>
                                <label for="statusSelect">{{ __('custom.inputs.is_active') }} <span class="text-danger">*</span></label>
                                @error('is_active')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="form-floating form-floating-outline">
                                <textarea id="bioInput" class="form-control" name="bio" rows="3">{{ old('bio', $data['record']->bio) }}</textarea>
                                <label for="bioInput">{{ __('custom.inputs.bio') }}</label>
                                @error('bio')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="mb-3">
                                <label class="form-label">{{ __('custom.words.current_image') }}</label>
                                @if($data['record']->image)
                                    <div>
                                        <img src="{{ $data['record']->imagePath }}" alt="Student Image" style="max-width: 150px; border-radius: 8px;">
                                    </div>
                                @endif
                            </div>
                            <div class="form-floating form-floating-outline">
                                <input id="imageInput" class="form-control" type="file" name="image" accept="image/*">
                                <label for="imageInput">{{ __('custom.words.new_image') }}</label>
                                @error('image')
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
        const countryCodes = [
            {code: "+20", name: "Egypt"},
            {code: "+1", name: "United States"},
            {code: "+44", name: "United Kingdom"},
            {code: "+966", name: "Saudi Arabia"},
            {code: "+971", name: "UAE"},
            {code: "+965", name: "Kuwait"},
            {code: "+974", name: "Qatar"},
            {code: "+968", name: "Oman"},
            {code: "+973", name: "Bahrain"}
        ];

        function populateCountryCodes(selectElement) {
            const currentCode = "{{ old('country_code', $data['record']->country_code) }}";
            countryCodes.forEach(country => {
                const option = document.createElement('option');
                option.value = country.code;
                option.textContent = `${country.name} (${country.code})`;
                if (country.code === currentCode) option.selected = true;
                selectElement.appendChild(option);
            });
        }

        populateCountryCodes(document.querySelector('.country-code-select'));
    </script>
@endsection

