@extends('admin.layouts.app')

@section('title', __('custom.words.create_new') . ' ' . __('custom.blog.blog'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Blog Create Form -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.create_new') . ' ' . __('custom.blog.blog') }}</h4>
                <a href="{{ route('dashboard.blogs.index') }}" class="btn btn-primary">
                    <i class="mdi mdi-arrow-left me-1"></i>
                    {{ __('custom.words.back') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.blogs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-4">
                        {{-- Blog Title --}}
                        <x-blog-title-input :locales="$data['locales']" />

                        {{-- Blog Excerpt --}}
                        <x-blog-excerpt-input :locales="$data['locales']" />

                        {{-- Blog Content --}}
                        <x-blog-content-input :locales="$data['locales']" />

                        {{-- Meta Data --}}
                        <x-meta-fields :locales="$data['locales']" />

                        {{-- General Settings --}}
                        <div class="col-md-12 mt-4">
                            <h5>{{ __('custom.words.general_settings') }}</h5>
                            <hr>
                        </div>

                        {{-- Creator/Author --}}
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select id="created_by"
                                    class="form-select @error('created_by') is-invalid @enderror"
                                    name="created_by">
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['users'] as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('created_by', auth()->id()) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <label for="created_by">{{ __('custom.inputs.creator') }}</label>
                                @error('created_by')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select id="is_active"
                                    class="form-select @error('is_active') is-invalid @enderror"
                                    name="is_active" required>
                                    <option value="">{{ __('custom.words.choose') }}</option>
                                    @foreach ($data['status'] as $stat)
                                        <option value="{{ $stat['value'] }}"
                                            {{ old('is_active', 1) == $stat['value'] ? 'selected' : '' }}>
                                            {{ $stat['lang'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="is_active">
                                    {{ __('custom.inputs.status') }} <span class="text-danger">*</span>
                                </label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Published Date --}}
                        <div class="col-md-6 mt-3">
                            <div class="form-floating form-floating-outline">
                                <input id="published_at"
                                    class="form-control @error('published_at') is-invalid @enderror"
                                    type="datetime-local"
                                    name="published_at"
                                    value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                                <label for="published_at">{{ __('custom.inputs.published_at') }}</label>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Featured Image --}}
                        <div class="col-md-6 mt-3">
                            <x-project-media-input
                                name="image"
                                :label="__('custom.inputs.featured_image')"
                                type="image"
                                :required="false"
                                :showHints="true"
                            />
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save me-1"></i>
                                {{ __('custom.words.create') }}
                            </button>
                            <a href="{{ route('dashboard.blogs.index') }}" class="btn btn-label-secondary">
                                <i class="mdi mdi-close me-1"></i>
                                {{ __('custom.words.cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('dashboard/assets/js/universal-media-handler.js') }}"></script>
<script>
    // Initialize CKEditor for all content textareas
    document.addEventListener('DOMContentLoaded', function() {
        const locales = @json($data['locales']);

        locales.forEach(locale => {
            ClassicEditor
                .create(document.querySelector(`#content_${locale}`), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    },
                    language: locale,
                    table: {
                        contentToolbar: [
                            'tableColumn',
                            'tableRow',
                            'mergeTableCells'
                        ]
                    }
                })
                .then(editor => {
                    // Fix CKEditor styling issues
                    const editableElement = editor.ui.view.editable.element;

                    // Set transparent background and visible text
                    editableElement.style.backgroundColor = 'transparent';
                    editableElement.style.color = '#566a7f';
                    editableElement.style.minHeight = '250px';

                    // Apply proper font family
                    editableElement.style.fontFamily = '"Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif';
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });
        });
    });
</script>
@endsection

@section('css')
<style>
    /* CKEditor Styling Fixes */
    .ck-editor__editable {
        background-color: transparent !important;
        color: #566a7f !important;
        border: 1px solid #d9dee3 !important;
        border-radius: 0.375rem !important;
    }

    .ck-editor__editable:focus {
        border-color: #696cff !important;
        box-shadow: 0 0 0.25rem 0.05rem rgba(105, 108, 255, 0.1) !important;
    }

    .ck-editor__editable p,
    .ck-editor__editable h1,
    .ck-editor__editable h2,
    .ck-editor__editable h3,
    .ck-editor__editable li,
    .ck-editor__editable td,
    .ck-editor__editable th {
        color: #566a7f !important;
    }

    .ck.ck-toolbar {
        background-color: #f5f5f9 !important;
        border: 1px solid #d9dee3 !important;
        border-bottom: none !important;
        border-radius: 0.375rem 0.375rem 0 0 !important;
    }

    .ck.ck-button:not(.ck-disabled):hover {
        background-color: rgba(105, 108, 255, 0.08) !important;
    }
</style>
@endsection
