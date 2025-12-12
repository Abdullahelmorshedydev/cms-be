@extends('dashboard.layouts.app')

@section('title', __('custom.words.create_new') . ' ' . __('custom.page.page'))


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.create_new') . ' ' . __('custom.page.page') }}</h4>
                <button id="addAccordionBtn" type="button" class="btn btn-primary">
                    {{ __('custom.page.add_section') }}
                </button>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.pages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-4">
                        {{-- ✅ Page Inputs --}}
                        @include('dashboard.pages.pages.partials.__page_inputs')

                        {{-- ✅ Page Meta Data --}}
                        <x-meta-fields :locales="$data['locales']" />

                        {{-- ✅ Sections Accordion --}}
                        <div class="accordion mt-4" id="dynamicAccordion">
                            @include('dashboard.pages.pages.partials.__accordion_item', ['index' => 0])
                        </div>

                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('custom.words.create') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('dashboard.pages.pages.partials.__accordion_template')
    @include('dashboard.pages.pages.partials.__sub_accordion_template')
@endsection

@section('js')
    <script src="{{ asset('dashboard/assets/js/universal-media-handler.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/page-builder.js') }}"></script>
@endsection
