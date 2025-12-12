<div class="modal fade modal-xl" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">
                    {{ __('custom.words.edit') . ' ' . __('custom.settings.settings') }}</h1>
                <h2 class="modal-title fs-5" style="margin-inline-start: 10px" id="modal-lead-name"></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" id="inputEn">
                                <label for="inputEn" id="labelEn"></label>
                                <div id="imagePreviewContainer" class="mt-3"></div>
                            </div>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" id="inputAr">
                                <label for="inputAr" id="labelAr"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="closeBtn"
                        data-bs-dismiss="modal">{{ __('custom.words.close') }}</button>
                    <button type="submit" class="btn btn-primary">
                        {{ __('custom.words.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
