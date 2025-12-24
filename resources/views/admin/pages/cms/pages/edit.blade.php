@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.words.page'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.edit') . ' ' . __('custom.words.page') }}</h4>
                <a href="{{ route('dashboard.cms.pages.index') }}" class="btn btn-secondary">
                    {{ __('custom.words.back') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.cms.pages.update', $data['record']->slug) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.pages.cms.pages._form', [
                        'page' => $data['record'],
                        'data' => $data,
                        'submitLabel' => __('custom.words.update')
                    ])
                </form>
            </div>
        </div>
    </div>
@endsection
