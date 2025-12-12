@extends('dashboard.layouts.app')

@section('title', __('custom.titles.settings'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        @if ($errors->any())
            <div class="bs-toast toast toast-ex animate__animated my-2 fade animate__shakeX show" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="mdi mdi-alert-circle me-2 text-danger"></i>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Leads List Table -->
        <div class="card mt-4">
            <div class="card-header">
                <div class="d-flex align-content-center justify-content-between align-items-center w-100">
                </div>
            </div>
            <div class="table-responsive table-data" id="lead-list">
                @include('dashboard.pages.settings.table')
            </div>
        </div>
    </div>

    @include('dashboard.pages.settings.partials.__edit_modal')
@endsection
