@extends('admin.layouts.app')

@section('title', __('custom.words.section_types'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-end">
                    <div class="d-flex align-items-end justify-content-end">
                        <div class="ms-2">
                            <a href="{{ route('dashboard.cms.section-types.create') }}" class="dt-button add-new btn btn-primary">
                                <span>
                                    <i class="mdi mdi-plus me-0 me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">{{ __('custom.words.add') . ' ' . __('custom.words.section_type') }}</span>
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
                                <th>{{ __('custom.columns.fields') }}</th>
                                <th>{{ __('custom.columns.created_at') }}</th>
                                <th>{{ __('custom.words.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sectionTypes as $sectionType)
                                <tr>
                                    <td>{{ $sectionType->name }}</td>
                                    <td>{{ $sectionType->slug }}</td>
                                    <td>
                                        @if($sectionType->fields)
                                            <span class="badge bg-info">{{ implode(', ', $sectionType->fields) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $sectionType->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.cms.section-types.show', $sectionType->id) }}">
                                                    <i class="mdi mdi-eye-outline me-1"></i> {{ __('custom.words.show') }}
                                                </a>
                                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.cms.section-types.edit', $sectionType->id) }}">
                                                    <i class="mdi mdi-pencil-outline me-1"></i> {{ __('custom.words.edit') }}
                                                </a>
                                                <a class="dropdown-item waves-effect text-danger delete-item" 
                                                   data-url="{{ route('dashboard.cms.section-types.destroy', $sectionType->id) }}" 
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
                                    <td colspan="5" class="text-center">{{ __('custom.words.no_data') }}</td>
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


