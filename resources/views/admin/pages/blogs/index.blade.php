@extends('admin.layouts.app')

@section('title', __('custom.titles.blogs'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-end">
                    <div class="d-flex align-items-end justify-content-end">
                        <div id="" class="" style="margin-right: 10px">
                            <select name="limit" class="form-select" onchange="passLimit()" id="limit">
                                <option value="10"
                                    {{ request()->has('limit') && request('limit') == 10 ? 'selected' : '' }}>10</option>
                                <option value="15"
                                    {{ request()->has('limit') && request('limit') == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ !request()->has('limit') ? 'selected' : '' }}
                                    {{ request()->has('limit') && request('limit') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50"
                                    {{ request()->has('limit') && request('limit') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100"
                                    {{ request()->has('limit') && request('limit') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        @can('blog.create')
                            <div id="" class="">
                                <a href="{{ route('dashboard.blogs.create') }}" class="dt-button add-new btn btn-primary"
                                    tabindex="0" tabindex="0" aria-controls="DataTables_Table_0" type="button">
                                    <span>
                                        <i class="mdi mdi-plus me-0 me-sm-1"></i>
                                        <span
                                            class="d-none d-sm-inline-block">{{ __('custom.words.add') . ' ' . __('custom.blog.blog') }}</span>
                                    </span>
                                </a>
                            </div>
                        @endcan
                        <div class=" ms-2 dropdown">
                            <button type="button" class="btn btn-primary dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('custom.words.actions') }}
                            </button>
                            <div class="dropdown-menu" style="">
                                @can('blog.import')
                                    <a class="dropdown-item waves-effect" data-bs-toggle="modal" data-bs-target="#ImportModal"
                                        href="#">
                                        <i class="mdi mdi-import me-1"></i>
                                        {{ __('custom.words.import') }}
                                    </a>
                                @endcan
                                @can('blog.export')
                                    <a class="dropdown-item waves-effect" href="{{ route('dashboard.blogs.export') }}">
                                        <i class="mdi mdi-download-box me-1"></i>
                                        {{ __('custom.words.export') }}
                                    </a>
                                @endcan
                                <a class="dropdown-item waves-effect" data-bs-toggle="modal" data-bs-target="#filterModal"
                                    href="#">
                                    <i class="mdi mdi-filter-check me-1"></i>
                                    {{ __('custom.words.filter') }}
                                </a>
                                @can('blog.delete')
                                    <a class="dropdown-item waves-effect delete-selection"
                                        data-url="{{ route('dashboard.blogs.delete') }}" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" href="#">
                                        <i class="mdi mdi-trash-can-outline me-1"></i>
                                        {{ __('custom.words.delete') }}
                                    </a>
                                @endcan
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="table-responsive table-data" id="user-list">
                @include('admin.pages.blogs.table')
            </div>
        </div>
    </div>

    @include('admin.pages.blogs.partials.__filter_date_modal')

    @include('admin.pages.blogs.partials.__import_modal')

    @include('admin.partials.__delete_modal')
@endsection
