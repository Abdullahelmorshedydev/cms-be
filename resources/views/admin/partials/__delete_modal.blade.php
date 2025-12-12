<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel">{{ __('custom.words.delete_selected') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="delete-selection-form" method="post">
                @csrf
                @method('DELETE')
                <input type="hidden" name="ids" class="checked-inputs">
                <div class="modal-body">
                    {{ __('custom.messages.delete_sure') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('custom.words.close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('custom.words.delete') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
