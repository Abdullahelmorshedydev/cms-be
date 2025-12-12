<!-- Modal -->
<div class="modal fade modal-xl" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterModalLabel">{{ __('custom.words.filter') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.users.index') }}" method="get">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="date_from">{{ __('custom.inputs.name') }}</label>
                            <input type="text" class="form-control" name="filters[name][value]" value="{{ request()->has('filters.name.value') ? request('filters.name.value') : '' }}">
                            <input type="hidden" name="filters[name][operator]" value="like">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="date_from">{{ __('custom.inputs.email') }}</label>
                            <input type="text" class="form-control" name="filters[email][value]" value="{{ request()->has('filters.email.value') ? request('filters.email.value') : '' }}">
                            <input type="hidden" name="filters[email][operator]" value="like">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="date_from">{{ __('custom.inputs.phone') }}</label>
                            <input type="text" class="form-control" name="filters[phone][value]" value="{{ request()->has('filters.phone.value') ? request('filters.phone.value') : '' }}">
                            <input type="hidden" name="filters[phone][operator]" value="like">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="date_from">{{ __('custom.inputs.is_admin') }}</label>
                            <select class="form-control" name="filters[is_admin]">
                                <option value="">{{ __('custom.words.choose') }}</option>
                                <option value="1" {{ request()->has('filters.is_admin') && request('filters.is_admin') == 1 ? 'selected' : '' }}>{{ __('custom.user.admin') }}</option>
                                <option value="0" {{ request()->has('filters.is_admin') && request('filters.is_admin') == 0 ? 'selected' : '' }}>{{ __('custom.user.customer') }}</option>
                            </select>
                        </div>
                        {{-- <div class="col-md-6 mt-3">
                            <label for="date_from">{{ __('custom.inputs.role') }}</label>
                            <select class="form-control" name="roles.name">
                                <option value="">{{ __('custom.words.choose') }}</option>
                                @foreach ($data['data']['roles'] as $role)
                                    <option value="{{ $role->name }}" {{ request()->has('roles.name') && request('roles.name') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('custom.words.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('custom.words.filter') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
