@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.service.service'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- services List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.edit') . ' ' . __('custom.service.service') }}</h4>
                <a href="{{ route('dashboard.services.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.services.update', $data['record']->slug) }}" method="POST" enctype="multipart/form-data">
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
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="short_descriptionEnInput" class="form-control" type="text" name="short_description[en]"
                                    value="{{ old('short_description.en', $data['record']->getTranslation('short_description', 'en')) }}">
                                <label for="short_descriptionEnInput">{{ __('custom.inputs.short_description_en') }}</label>
                                @error('short_description.en')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="short_descriptionArInput" class="form-control" type="text" name="short_description[ar]"
                                    value="{{ old('short_description.ar', $data['record']->getTranslation('short_description', 'ar')) }}">
                                <label for="short_descriptionArInput">{{ __('custom.inputs.short_description_ar') }}</label>
                                @error('short_description.ar')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <textarea name="description[en]" id="descriptionEnInput" class="form-control">{!! old('description.en', $data['record']->getTranslation('description', 'en')) !!}</textarea>
                                <label for="descriptionEnInput">{{ __('custom.inputs.description_en') }}</label>
                                @error('description.en')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <textarea name="description[ar]" id="descriptionArInput" class="form-control">{!! old('description.ar', $data['record']->getTranslation('description', 'ar')) !!}</textarea>
                                <label for="descriptionArInput">{{ __('custom.inputs.description_ar') }}</label>
                                @error('description.ar')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select id="tagsSelect" class="form-control select2" name="tags[]">
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['tags'] as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $data['record']->tags()->pluck('tag_id')->toArray())) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="tagsSelect">{{ __('custom.inputs.tags') }}</label>
                                @error('tags')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                                @error('tags.*')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
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
                                    <img src="{{ $data['record']->image->url }}" alt="">
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
