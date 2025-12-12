<div class="modal fade" id="ImportModal" tabindex="-1" aria-labelledby="ImportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ImportModalLabel">
                    {{ __('custom.words.import') . ' ' . __('custom.words.data') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.users.import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="file" class="form-control" name="excel_file" accept=".xlsx">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('custom.words.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('custom.words.import') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
