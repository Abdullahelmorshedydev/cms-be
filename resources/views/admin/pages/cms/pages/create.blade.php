@extends('admin.layouts.app')

@section('title', __('custom.words.create_new') . ' ' . __('custom.words.page'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.create_new') . ' ' . __('custom.words.page') }}</h4>
                <a href="{{ route('dashboard.cms.pages.index') }}" class="btn btn-secondary">
                    {{ __('custom.words.back') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.cms.pages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include(
                        'admin.pages.cms.pages._form',
                        [
                            'page' => null,
                            'data' => $data ?? ['status' => []],
                            'submitLabel' => __('custom.words.create')
                        ]
                    )
                </form>
            </div>
        </div>
    </div>
@endsection
