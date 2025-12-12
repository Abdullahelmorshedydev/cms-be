<table class="table">
    <thead class="table-light">
        <tr>
            <th>
                <input type="checkbox" class="form-check-input selectAllCheckboxInputs">
            </th>
            <th>{{ __('custom.columns.image') }}</th>
            <th>{{ __('custom.columns.title') }}</th>
            <th>{{ __('custom.columns.creator') }}</th>
            <th>{{ __('custom.columns.published_at') }}</th>
            <th>{{ __('custom.columns.comments_count') }}</th>
            <th>{{ __('custom.columns.status') }}</th>
            <th>{{ __('custom.words.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data['data']['data'] as $blog)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input checkboxInput" value="{{ $blog->id }}">
                </td>
                <td>
                    <div class="avatar avatar-md">
                        <img src="{{ $blog->imagePath }}" alt="{{ $blog->title }}" class="rounded">
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-semibold">{{ Str::limit($blog->title, 50) }}</span>
                        @if($blog->excerpt)
                            <small class="text-muted">{{ Str::limit($blog->excerpt, 80) }}</small>
                        @endif
                    </div>
                </td>
                <td>
                    @if($blog->creator)
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-2">
                                <img src="{{ $blog->creator->imagePath }}" alt="{{ $blog->creator->name }}" class="rounded-circle">
                            </div>
                            <div>
                                <span class="fw-medium">{{ $blog->creator->name }}</span>
                                <small class="text-muted d-block">{{ $blog->creator->email }}</small>
                            </div>
                        </div>
                    @else
                        <span class="text-muted">{{ __('custom.words.not_available') }}</span>
                    @endif
                </td>
                <td>
                    @if($blog->published_at)
                        <span>{{ $blog->published_at->format('Y-m-d') }}</span>
                        <small class="text-muted d-block">{{ $blog->published_at->diffForHumans() }}</small>
                    @else
                        <span class="badge bg-label-warning">{{ __('custom.words.draft') }}</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-comment-multiple-outline me-1"></i>
                        <span>{{ $blog->comments_count ?? $blog->allComments->count() }}</span>
                    </div>
                </td>
                <td>
                    <span class="badge bg-label-{{ $blog->is_active->value == 1 ? 'success' : 'secondary' }}">
                        {{ $blog->is_active->lang() }}
                    </span>
                </td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            @can('blog.show')
                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.blogs.show', $blog->slug) }}">
                                    <i class="mdi mdi-eye-outline me-1"></i>
                                    {{ __('custom.words.view') }}
                                </a>
                            @endcan
                            @can('blog.edit')
                                <a class="dropdown-item waves-effect" href="{{ route('dashboard.blogs.edit', $blog->slug) }}">
                                    <i class="mdi mdi-pen me-1"></i>
                                    {{ __('custom.words.edit') }}
                                </a>
                            @endcan
                            @can('blog.delete')
                                <a class="dropdown-item waves-effect delete-btn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-url="{{ route('dashboard.blogs.destroy', $blog->slug) }}">
                                    <i class="mdi mdi-trash-can-outline me-1"></i>
                                    {{ __('custom.words.delete') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="mb-3">
                        <i class="mdi mdi-clipboard-text-outline" style="font-size: 48px; color: #ccc;"></i>
                    </div>
                    <h5 class="text-muted">{{ __('custom.words.no_data') }}</h5>
                    @can('blog.create')
                        <a href="{{ route('dashboard.blogs.create') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="mdi mdi-plus me-1"></i>
                            {{ __('custom.words.add') . ' ' . __('custom.blog.blog') }}
                        </a>
                    @endcan
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="mt-3 px-3">
    <x-pagination :meta="$data['meta']" />
</div>
