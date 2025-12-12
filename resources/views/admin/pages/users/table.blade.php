<table class="table">
    <thead class="table-light">
        <tr>
            <th>
                <input type="checkbox" class="form-check-input selectAllCheckboxInputs">
            </th>
            <th>{{ __('custom.columns.name') }}</th>
            <th>{{ __('custom.columns.email') }}</th>
            <th>{{ __('custom.columns.role_name') }}</th>
            <th>{{ __('custom.columns.activation') }}</th>
            <th>{{ __('custom.words.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['data']['data'] as $user)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input checkboxInput" value="{{ $user->id }}">
                </td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->getRoleNames()->count() ? $user->getRoleNames()[0] : __('custom.user.customer') }}</td>
                <td>
                    {{ $user->is_active->lang() }}
                </td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" style="">
                            @can('user.edit')
                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.users.edit', $user) }}">
                                    <i class="mdi mdi-pen me-1"></i>
                                    {{ __('custom.words.edit') }}
                                </a>
                            @endcan
                            @can('user.delete')
                                <a class="dropdown-item waves-effect delete-btn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-url="{{ route('dashboard.users.destroy', $user) }}"><i
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
    {{-- {{ $users->appends(request()->all())->links() }} --}}
    <x-pagination :meta="$data['meta']" />
</div>
