@extends('admin.layouts.app')

@section('title', __('custom.words.create_new') . ' ' . __('custom.service_category.service_category'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- service categories List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.create_new') . ' ' . __('custom.service_category.service_category') }}</h4>
                <a href="{{ route('dashboard.service-categories.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.service-categories.store') }}" method="POST">
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
                                <button type="submit" class="btn btn-primary">{{ __('custom.words.create') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
