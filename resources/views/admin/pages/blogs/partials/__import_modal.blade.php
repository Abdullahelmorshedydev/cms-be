<!-- Import Modal -->
<div class="modal fade" id="ImportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-import me-2"></i>
                    {{ __('custom.words.import') }} {{ __('custom.blog.blogs') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.blogs.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('custom.words.select_file') }}</label>
                        <input type="file"
                            class="form-control"
                            name="file"
                            accept=".csv,.txt"
                            required>
                        <div class="form-text">
                            <i class="mdi mdi-information-outline me-1"></i>
                            {{ __('custom.words.csv_file_only') }}
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-2">
                            <i class="mdi mdi-lightbulb-outline me-2"></i>
                            {{ __('custom.words.import_instructions') }}
                        </h6>
                        <ul class="mb-0 ps-3">
                            <li>{{ __('custom.blog.import_instruction_1') }}</li>
                            <li>{{ __('custom.blog.import_instruction_2') }}</li>
                            <li>{{ __('custom.blog.import_instruction_3') }}</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <a href="#" class="btn btn-sm btn-label-primary">
                            <i class="mdi mdi-download me-1"></i>
                            {{ __('custom.words.download_sample') }}
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        {{ __('custom.words.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-import me-1"></i>
                        {{ __('custom.words.import') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
