@extends('admin.layouts.app')

@section('title', __('custom.words.pages'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-end">
                    <div class="d-flex align-items-end justify-content-end">
                        <div class="ms-2">
                            <a href="{{ route('dashboard.cms.pages.create') }}" class="dt-button add-new btn btn-primary">
                                <span>
                                    <i class="mdi mdi-plus me-0 me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">{{ __('custom.words.add') . ' ' . __('custom.words.page') }}</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('custom.columns.name') }}</th>
                                <th>{{ __('custom.columns.slug') }}</th>
                                @if(isset($pages->first()->is_active))
                                    <th>{{ __('custom.columns.activation') }}</th>
                                @endif
                                <th>{{ __('custom.columns.created_at') }}</th>
                                <th>{{ __('custom.words.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pages as $page)
                                <tr>
                                    <td>{{ $page->name }}</td>
                                    <td>{{ $page->slug }}</td>
                                    @if(isset($page->is_active))
                                        <td>
                                            <span class="badge bg-{{ $page->is_active ? 'success' : 'danger' }}">
                                                {{ $page->is_active ? __('custom.words.active') : __('custom.words.inactive') }}
                                            </span>
                                        </td>
                                    @endif
                                    <td>{{ $page->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.cms.pages.show', $page->id) }}">
                                                    <i class="mdi mdi-eye-outline me-1"></i> {{ __('custom.words.show') }}
                                                </a>
                                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.cms.pages.edit', $page->id) }}">
                                                    <i class="mdi mdi-pencil-outline me-1"></i> {{ __('custom.words.edit') }}
                                                </a>
                                                <a class="dropdown-item waves-effect text-danger delete-item" 
                                                   data-url="{{ route('dashboard.cms.pages.destroy', $page->id) }}" 
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#deleteModal">
                                                    <i class="mdi mdi-trash-can-outline me-1"></i> {{ __('custom.words.delete') }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ isset($pages->first()->is_active) ? '5' : '4' }}" class="text-center">{{ __('custom.words.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.__delete_modal')
@endsection

