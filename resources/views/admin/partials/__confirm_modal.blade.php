<!-- Beautiful Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="mb-3">
                    <div class="avatar avatar-xl mx-auto mb-3" id="confirmIconContainer">
                        <span class="avatar-initial rounded-circle" id="confirmIcon">
                            <i class="mdi mdi-alert-circle-outline mdi-48px"></i>
                        </span>
                    </div>
                </div>
                <h5 class="modal-title mb-2" id="confirmModalLabel">Confirm Action</h5>
                <p class="text-muted mb-0" id="confirmMessage">Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" id="confirmCancelBtn">
                    <i class="mdi mdi-close me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmOkBtn">
                    <i class="mdi mdi-check me-1"></i>Confirm
                </button>
            </div>
        </div>
    </div>
</div>
