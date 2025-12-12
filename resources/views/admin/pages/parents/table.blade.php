<table class="table">
    <thead class="table-light">
        <tr>
            <th>
                <input type="checkbox" class="form-check-input selectAllCheckboxInputs">
            </th>
            <th>{{ __('custom.columns.name') }}</th>
            <th>{{ __('custom.inputs.email') }}</th>
            <th>{{ __('custom.inputs.phone') }}</th>
            <th>{{ __('custom.inputs.relationship_to_student') }}</th>
            <th>{{ __('custom.parent.children_count') }}</th>
            <th>{{ __('custom.columns.activation') }}</th>
            <th>{{ __('custom.words.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data['data']['data'] as $parent)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input checkboxInput" value="{{ $parent->id }}">
                </td>
                <td>{{ $parent->name }}</td>
                <td>{{ $parent->email }}</td>
                <td>{{ $parent->country_code }} {{ $parent->phone }}</td>
                <td>
                    @if($parent->relationship_to_student)
                        <span class="badge bg-info">
                            {{ ucfirst($parent->relationship_to_student) }}
                        </span>
                    @else
                        {{ __('custom.words.not_available') }}
                    @endif
                </td>
                <td>
                    <span class="badge bg-primary">
                        {{ $parent->children->count() }}
                    </span>
                </td>
                <td>
                    {{ $parent->is_active->lang() }}
                </td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" style="">
                            @can('parent.show')
                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.parents.show', $parent) }}">
                                    <i class="mdi mdi-eye me-1"></i>
                                    {{ __('custom.words.view') }}
                                </a>
                            @endcan
                            @can('parent.edit')
                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.parents.edit', $parent) }}">
                                    <i class="mdi mdi-pen me-1"></i>
                                    {{ __('custom.words.edit') }}
                                </a>
                            @endcan
                            @can('parent.delete')
                                <a class="dropdown-item waves-effect delete-btn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-url="{{ route('dashboard.parents.destroy', $parent) }}"><i
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

