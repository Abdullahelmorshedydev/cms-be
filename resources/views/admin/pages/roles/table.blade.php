@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

<table class="table">
    <thead class="table-light">
        <tr>
            <th>
                <input type="checkbox" class="form-check-input selectAllCheckboxInputs">
            </th>
            <th>{{ __('custom.columns.name') }}</th>
            @canany(['role.edit', 'role.delete'])
                <th>{{ __('custom.words.actions') }}</th>
            @endcanany
        </tr>
    </thead>
    <tbody>
        @foreach ($data['data']['data'] as $role)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input checkboxInput" value="{{ $role->id }}">
                </td>
                <td>{{ json_decode($role->display_name)->{LaravelLocalization::getCurrentLocale()} }}</td>
                @canany(['role.edit', 'role.delete'])
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu" style="">
                                @can('role.edit')
                                    <a class="dropdown-item waves-effect" href="{{ route('dashboard.roles.edit', $role) }}">
                                        <i class="mdi mdi-pen me-1"></i>
                                        {{ __('custom.words.edit') }}
                                    </a>
                                @endcan
                                @can('role.delete')
                                    <a class="dropdown-item waves-effect delete-btn" href="#" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-url="{{ route('dashboard.roles.destroy', $role->id) }}"><i
                                            class="mdi mdi-trash-can-outline me-1"></i>
                                        {{ __('custom.words.delete') }}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </td>
                @endcanany
            </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-3 px-3">
    {{-- {{ $data['data']->appends(request()->all())->links() }} --}}
    <x-pagination :meta="$data['meta']" />
</div>
