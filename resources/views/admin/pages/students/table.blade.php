<table class="table">
    <thead class="table-light">
        <tr>
            <th>
                <input type="checkbox" class="form-check-input selectAllCheckboxInputs">
            </th>
            <th>{{ __('custom.columns.name') }}</th>
            <th>{{ __('custom.inputs.student_id') }}</th>
            <th>{{ __('custom.inputs.grade') }}</th>
            <th>{{ __('custom.inputs.class') }}</th>
            <th>{{ __('custom.inputs.parent_name') }}</th>
            <th>{{ __('custom.columns.activation') }}</th>
            <th>{{ __('custom.words.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data['data']['data'] as $student)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input checkboxInput" value="{{ $student->id }}">
                </td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->student_id ?? __('custom.words.not_available') }}</td>
                <td>{{ $student->grade ?? __('custom.words.not_available') }}</td>
                <td>{{ $student->class ?? __('custom.words.not_available') }}</td>
                <td>{{ $student->parent?->name ?? __('custom.student.no_parent_assigned') }}</td>
                <td>
                    {{ $student->is_active->lang() }}
                </td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" style="">
                            @can('student.show')
                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.students.show', $student) }}">
                                    <i class="mdi mdi-eye me-1"></i>
                                    {{ __('custom.words.view') }}
                                </a>
                            @endcan
                            @can('student.edit')
                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.students.edit', $student) }}">
                                    <i class="mdi mdi-pen me-1"></i>
                                    {{ __('custom.words.edit') }}
                                </a>
                            @endcan
                            @can('student.delete')
                                <a class="dropdown-item waves-effect delete-btn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-url="{{ route('dashboard.students.destroy', $student) }}"><i
                                        class="mdi mdi-trash-can-outline me-1"></i>
                                    {{ __('custom.words.delete') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">{{ __('custom.words.no_data') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="mt-3 px-3">
    <x-pagination :meta="$data['meta']" />
</div>

