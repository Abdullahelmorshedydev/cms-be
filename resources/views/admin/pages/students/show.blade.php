@extends('dashboard.layouts.app')

@section('title', __('custom.student.student_details'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>{{ __('custom.student.student_details') }}</h4>
                <div>
                    @can('student.edit')
                        <a href="{{ route('dashboard.students.edit', $data['record']->id) }}" class="btn btn-warning">
                            <i class="mdi mdi-pen me-1"></i>{{ __('custom.words.edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('dashboard.students.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Student Image --}}
                    <div class="col-md-3 text-center mb-4">
                        <img src="{{ $data['record']->imagePath }}" alt="{{ $data['record']->name }}"
                             class="img-fluid rounded" style="max-width: 200px;">
                    </div>

                    {{-- Student Information --}}
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>{{ __('custom.inputs.name') }}:</strong>
                                <p>{{ $data['record']->name }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>{{ __('custom.inputs.email') }}:</strong>
                                <p>{{ $data['record']->email }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>{{ __('custom.inputs.phone') }}:</strong>
                                <p>{{ $data['record']->country_code }} {{ $data['record']->phone }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>{{ __('custom.inputs.student_id') }}:</strong>
                                <p>{{ $data['record']->student_id ?? __('custom.words.not_available') }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>{{ __('custom.inputs.gender') }}:</strong>
                                <p>{{ $data['record']->gender?->lang() ?? __('custom.words.not_available') }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>{{ __('custom.inputs.date_of_birth') }}:</strong>
                                <p>{{ $data['record']->date_of_birth?->format('Y-m-d') ?? __('custom.words.not_available') }}</p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>{{ __('custom.inputs.is_active') }}:</strong>
                                <p>
                                    <span class="badge bg-{{ $data['record']->is_active->value == 1 ? 'success' : 'danger' }}">
                                        {{ $data['record']->is_active->lang() }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Academic Information --}}
                    <div class="col-12 mt-4">
                        <h5 class="mb-3">{{ __('custom.student.academic_info') }}</h5>
                        <hr>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>{{ __('custom.inputs.grade') }}:</strong>
                        <p>{{ $data['record']->grade ?? __('custom.words.not_available') }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>{{ __('custom.inputs.class') }}:</strong>
                        <p>{{ $data['record']->class ?? __('custom.words.not_available') }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>{{ __('custom.inputs.academic_year') }}:</strong>
                        <p>{{ $data['record']->academic_year ?? __('custom.words.not_available') }}</p>
                    </div>

                    {{-- Parent Information --}}
                    <div class="col-12 mt-4">
                        <h5 class="mb-3">{{ __('custom.parent.parent_info') }}</h5>
                        <hr>
                    </div>

                    @if($data['record']->parent)
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('custom.inputs.parent_name') }}:</strong>
                            <p>{{ $data['record']->parent->name }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>{{ __('custom.inputs.email') }}:</strong>
                            <p>{{ $data['record']->parent->email }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>{{ __('custom.inputs.phone') }}:</strong>
                            <p>{{ $data['record']->parent->country_code }} {{ $data['record']->parent->phone }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="{{ route('dashboard.parents.show', $data['record']->parent->id) }}" class="btn btn-sm btn-info">
                                {{ __('custom.parent.view_parent') }}
                            </a>
                        </div>
                    @else
                        <div class="col-12">
                            <p class="text-muted">{{ __('custom.student.no_parent_assigned') }}</p>
                        </div>
                    @endif

                    {{-- Additional Information --}}
                    @if($data['record']->bio)
                        <div class="col-12 mt-4">
                            <h5 class="mb-3">{{ __('custom.inputs.bio') }}</h5>
                            <hr>
                        </div>

                        <div class="col-12 mb-3">
                            <p>{{ $data['record']->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

