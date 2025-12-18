@extends('admin.layouts.app')

@section('title', __('custom.titles.profile'))

@section('content')
    @php
        $user = auth()->user();
    @endphp
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            <img src="{{ $user->image_path }}" alt="user image"
                                class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" style="width: 200px" />
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div
                                class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>{{ $user->name }}</h4>
                                    <p>{{ $user->email }}</p>
                                    <ul
                                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                        @if($user->job_title)
                                            <li class="list-inline-item">
                                                <i class="fa-solid fa-briefcase me-1 mdi-20px"></i>
                                                <span class="fw-medium">{{ $user->job_title }}</span>
                                            </li>
                                        @endif
                                        @if($user->bio)
                                            <li class="list-inline-item">
                                                <i class="mdi mdi-information-outline me-1 mdi-20px"></i>
                                                <span class="fw-medium">{{ Str::limit($user->bio, 50) }}</span>
                                            </li>
                                        @endif
                                        <li class="list-inline-item">
                                            <i class="mdi mdi-calendar-blank-outline me-1 mdi-20px"></i>
                                            <span class="fw-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>{{ __('custom.words.update_general_profile') }}</h4>
                    </div>
                    <div class="card-body mb-4">
                        <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                {{-- Common Fields --}}
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="nameInput">{{ __('custom.inputs.name') }}</label>
                                        <input id="nameInput" class="form-control" type="text" name="name"
                                            value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="emailInput">{{ __('custom.inputs.email') }}</label>
                                        <input id="emailInput" class="form-control" type="email" name="email"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <div class="form-group" style="position: relative">
                                        <label for="phone">{{ __('custom.inputs.phone') }}</label>
                                        <select name="country_code" id="country_code" class="form-select"
                                            style="max-width: 100px;position: absolute;top: 22px;right: 0">
                                            <option value="+20" {{ old('country_code', $user->country_code) == '+20' ? 'selected' : '' }}>+20</option>
                                            <option value="+966" {{ old('country_code', $user->country_code) == '+966' ? 'selected' : '' }}>+966</option>
                                            <option value="+971" {{ old('country_code', $user->country_code) == '+971' ? 'selected' : '' }}>+971</option>
                                            <option value="+974" {{ old('country_code', $user->country_code) == '+974' ? 'selected' : '' }}>+974</option>
                                            <option value="+973" {{ old('country_code', $user->country_code) == '+973' ? 'selected' : '' }}>+973</option>
                                            <option value="+972" {{ old('country_code', $user->country_code) == '+972' ? 'selected' : '' }}>+972</option>
                                            <option value="+968" {{ old('country_code', $user->country_code) == '+968' ? 'selected' : '' }}>+968</option>
                                            <option value="+965" {{ old('country_code', $user->country_code) == '+965' ? 'selected' : '' }}>+965</option>
                                        </select>
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" id="phone"
                                            class="form-control" placeholder="{{ __('custom.inputs.phone') }}"
                                            aria-label="{{ __('custom.inputs.phone') }}" required />
                                        @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @error('country_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="imageInput">{{ __('custom.inputs.image') }}</label>
                                        <input id="imageInput" class="form-control" type="file" name="image"
                                            accept="image/*">
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Admin Profile Fields --}}
                                @include('admin.auth.partials.admin-profile-fields')

                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-content-save me-1"></i>
                                            {{ __('custom.words.update') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between">
                        <h4>{{ __('custom.words.change_password') }}</h4>
                    </div>
                    <div class="card-body mb-4">
                        <form action="{{ route('dashboard.profile.change_password') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="passwordENInput">{{ __('custom.inputs.password') }}</label>
                                        <input id="passwordENInput" class="form-control" type="password" name="password">
                                        @error('password')
                                            <div class="text-danger"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="new_passwordENInput">{{ __('custom.inputs.new_password') }}</label>
                                        <input id="new_passwordENInput" class="form-control" type="password"
                                            name="new_password">
                                        @error('new_password')
                                            <div class="text-danger"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label
                                            for="new_password_confirmationENInput">{{ __('custom.inputs.new_password_confirmation') }}</label>
                                        <input id="new_password_confirmationENInput" class="form-control" type="password"
                                            name="new_password_confirmation">
                                        @error('new_password_confirmation')
                                            <div class="text-danger"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-primary">{{ __('custom.words.update') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
