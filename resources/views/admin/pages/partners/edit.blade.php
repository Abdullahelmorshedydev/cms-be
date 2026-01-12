@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.partner.partner'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- partners List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.edit') . ' ' . __('custom.partner.partner') }}</h4>
                <a href="{{ route('dashboard.partners.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.partners.update', $data['record']->slug) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="nameEnInput" class="form-control" type="text" name="name[en]"
                                    value="{{ old('name.en', $data['record']->getTranslation('name', 'en')) }}">
                                <label for="nameEnInput">{{ __('custom.inputs.name_en') }}</label>
                                @error('name.en')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="nameArInput" class="form-control" type="text" name="name[ar]"
                                    value="{{ old('name.ar', $data['record']->getTranslation('name', 'ar')) }}">
                                <label for="nameArInput">{{ __('custom.inputs.name_ar') }}</label>
                                @error('name.ar')
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
                        @if ($data['record']->image)
                            <div class="col-md-12 mt-3">
                                <div class="form-floating form-floating-outline">
                                    <img src="{{ $data['record']->image->url }}" alt="" style="width: 200px; height: 200px">
                                </div>
                            </div>
                        @endif
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
