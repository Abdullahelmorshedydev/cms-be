<table class="table">
    <thead class="table-light">
        <tr>
            <th>
                <input type="checkbox" class="form-check-input selectAllCheckboxInputs">
            </th>
            <th>{{ __('custom.columns.name') }}</th>
            <th>{{ __('custom.columns.slug') }}</th>
            <th>{{ __('custom.columns.services_count') }}</th>
            <th>{{ __('custom.words.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['data']['data'] as $category)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input checkboxInput" value="{{ $category->id }}">
                </td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->services_count ?? ($category->services ? $category->services->count() : 0) }}</td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" style="">
                            @can('service-category.edit')
                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.service-categories.edit', $category->slug) }}">
                                    <i class="mdi mdi-pen me-1"></i>
                                    {{ __('custom.words.edit') }}
                                </a>
                            @endcan
                            @can('service-category.delete')
                                <a class="dropdown-item waves-effect delete-btn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-url="{{ route('dashboard.service-categories.destroy', $category->slug) }}"><i
                                        class="mdi mdi-trash-can-outline me-1"></i>
                                    {{ __('custom.words.delete') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-3 px-3">
    <x-pagination :meta="$data['meta']" />
</div>
