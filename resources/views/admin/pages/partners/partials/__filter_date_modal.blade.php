<!-- Modal -->
<div class="modal fade modal-xl" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="filterModalLabel">{{ __('custom.words.filter') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.partners.index') }}" method="get">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label for="date_from">{{ __('custom.inputs.name') }}</label>
                            <input type="text" class="form-control" name="filters[name][value]"
                                value="{{ request()->has('filters.name.value') ? request('filters.name.value') : '' }}">
                            <input type="hidden" name="filters[name][operator]" value="like">
                        </div>
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