@extends('dashboard.layouts.app')

@section('title', __('custom.parent.parent_details'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>{{ __('custom.parent.parent_details') }}</h4>
                <div>
                    @can('parent.edit')
                        <a href="{{ route('dashboard.parents.edit', $data['record']->id) }}" class="btn btn-warning">
                            <i class="mdi mdi-pen me-1"></i>{{ __('custom.words.edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('dashboard.parents.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Parent Image --}}
                    <div class="col-md-3 text-center mb-4">
                        <img src="{{ $data['record']->imagePath }}" alt="{{ $data['record']->name }}"
                             class="img-fluid rounded" style="max-width: 200px;">
                    </div>

                    {{-- Parent Information --}}
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

                    {{-- Parent Specific Information --}}
                    <div class="col-12 mt-4">
                        <h5 class="mb-3">{{ __('custom.parent.contact_info') }}</h5>
                        <hr>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>{{ __('custom.inputs.relationship_to_student') }}:</strong>
                        <p>
                            @if($data['record']->relationship_to_student)
                                <span class="badge bg-info">{{ ucfirst($data['record']->relationship_to_student) }}</span>
                            @else
                                {{ __('custom.words.not_available') }}
                            @endif
                        </p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>{{ __('custom.inputs.occupation') }}:</strong>
                        <p>{{ $data['record']->occupation ?? __('custom.words.not_available') }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>{{ __('custom.inputs.national_id') }}:</strong>
                        <p>{{ $data['record']->national_id ?? __('custom.words.not_available') }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>{{ __('custom.inputs.emergency_contact') }}:</strong>
                        <p>{{ $data['record']->emergency_contact ?? __('custom.words.not_available') }}</p>
                    </div>

                    {{-- Children Information --}}
                    <div class="col-12 mt-4">
                        <h5 class="mb-3">{{ __('custom.words.children') }} ({{ $data['record']->children->count() }})</h5>
                        <hr>
                    </div>

                    @if($data['record']->children->count() > 0)
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('custom.inputs.name') }}</th>
                                            <th>{{ __('custom.inputs.student_id') }}</th>
                                            <th>{{ __('custom.inputs.grade') }}</th>
                                            <th>{{ __('custom.inputs.class') }}</th>
                                            <th>{{ __('custom.words.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['record']->children as $child)
                                            <tr>
                                                <td>{{ $child->name }}</td>
                                                <td>{{ $child->student_id ?? __('custom.words.not_available') }}</td>
                                                <td>{{ $child->grade ?? __('custom.words.not_available') }}</td>
                                                <td>{{ $child->class ?? __('custom.words.not_available') }}</td>
                                                <td>
                                                    @can('student.show')
                                                        <a href="{{ route('dashboard.students.show', $child->id) }}" class="btn btn-sm btn-info">
                                                            {{ __('custom.words.view') }}
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="col-12">
                            <p class="text-muted">{{ __('custom.parent.no_children') }}</p>
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

