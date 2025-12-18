@extends('admin.layouts.app')

@section('title', __('custom.words.edit') . ' ' . __('custom.page.page'))

@section('css')
    <style>
        .media-item {
            position: relative;
            display: inline-block;
        }

        .media-item img, .media-item video {
            display: block;
            max-width: 150px;
            height: auto;
        }

        .media-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            color: #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            text-align: center;
            line-height: 24px;
            font-size: 12px;
            cursor: pointer;
            z-index: 10;
            opacity: 0;
        }

        .media-item:hover .media-remove {
            opacity: 1;
        }
    </style>
@endsection

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
                <form action="{{ route('dashboard.pages.update', $data['record']->slug) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mt-4">
                        {{-- ✅ Page Inputs --}}
                        @include('admin.pages.pages.partials.__page_inputs')

                        {{-- ✅ Page Meta Data --}}
                        <x-meta-fields :record="$data['record']" :locales="$data['locales']" />

                        {{-- ✅ Deleted Sections --}}
                        <div id="deletedSectionsContainer"></div>

                        {{-- ✅ Removed Media --}}
                        <div id="removedMediaContainer"></div>

                        {{-- ✅ Sections Accordion --}}
                        <div class="accordion mt-4" id="dynamicAccordion">
                            @if (!empty($data['record']->sections))
                                @foreach ($data['record']->sections as $index => $section)
                                    @include('admin.pages.pages.partials.__accordion_item', [
                                        'index' => $index,
                                        'section' => $section,
                                    ])
                                @endforeach
                            @else
                                @include('admin.pages.pages.partials.__accordion_item', ['index' => 0])
                            @endif
                        </div>

                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('custom.words.update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.pages.pages.partials.__accordion_template')
    @include('admin.pages.pages.partials.__sub_accordion_template')
@endsection

@section('js')
    <script src="{{ asset('dashboard/assets/js/universal-media-handler.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/page-builder.js') }}"></script>
@endsection
