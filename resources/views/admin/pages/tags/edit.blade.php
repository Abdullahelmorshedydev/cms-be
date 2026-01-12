@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.tag.tag'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- tags List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.edit') . ' ' . __('custom.tag.tag') }}</h4>
                <a href="{{ route('dashboard.tags.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.tags.update', $data['record']->slug) }}" method="POST" enctype="multipart/form-data">
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
                                <select id="statusSelect" class="form-control" name="status">
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['status'] as $status)
                                        <option value="{{ $status['value'] }}"
                                            {{ old('status', $data['record']->status->value) == $status['value'] ? 'selected' : '' }}>
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
