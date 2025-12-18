@extends('admin.layouts.app')

@section('title', __('custom.blog.blog_details'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Blog Content -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">{{ $blog->title }}</h4>
                            <div class="text-muted">
                                <small>
                                    <i class="mdi mdi-calendar me-1"></i>
                                    {{ $blog->published_at ? $blog->published_at->format('F d, Y') : __('custom.words.draft') }}
                                </small>
                                <small class="ms-3">
                                    <i class="mdi mdi-clock-outline me-1"></i>
                                    {{ $blog->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        <div>
                            @can('blog.edit')
                                <a href="{{ route('dashboard.blogs.edit', $blog->slug) }}" class="btn btn-primary btn-sm">
                                    <i class="mdi mdi-pen me-1"></i>
                                    {{ __('custom.words.edit') }}
                                </a>
                            @endcan
                            <a href="{{ route('dashboard.blogs.index') }}" class="btn btn-label-secondary btn-sm">
                                <i class="mdi mdi-arrow-left me-1"></i>
                                {{ __('custom.words.back') }}
                            </a>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    @if($blog->image)
                        <img src="{{ $blog->imagePath }}" alt="{{ $blog->title }}" class="card-img-top">
                    @endif

                    <div class="card-body">
                        <!-- Excerpt -->
                        @if($blog->excerpt)
                            <div class="alert alert-info">
                                <strong>{{ __('custom.words.excerpt') }}:</strong>
                                <p class="mb-0 mt-2">{{ $blog->excerpt }}</p>
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="blog-content">
                            {!! $blog->content !!}
                        </div>

                        <!-- Tags/Keywords -->
                        @if($blog->meta_keywords)
                            <hr class="my-4">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(explode(',', $blog->meta_keywords) as $keyword)
                                    <span class="badge bg-label-primary">{{ trim($keyword) }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="mdi mdi-comment-multiple-outline me-2"></i>
                            {{ __('custom.blog.comments') }}
                            <span class="badge bg-label-primary">{{ $blog->allComments->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($blog->comments as $comment)
                            <div class="comment-item mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-start">
                                        <div class="avatar avatar-sm me-3">
                                            @if($comment->user)
                                                <img src="{{ $comment->user->imagePath }}" alt="{{ $comment->user->name }}" class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ substr($comment->name ?? 'G', 0, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-1">
                                                {{ $comment->user ? $comment->user->name : $comment->name }}
                                                @if(!$comment->user)
                                                    <span class="badge bg-label-info badge-sm">{{ __('custom.words.guest') }}</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <!-- Status Badge -->
                                        <span class="badge bg-label-{{ $comment->is_active->value == 1 ? 'success' : 'warning' }}">
                                            {{ $comment->is_active->lang() }}
                                        </span>

                                        <!-- Actions -->
                                        @canany(['blog.comment.approve', 'blog.comment.reject', 'blog.comment.delete'])
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @can('blog.comment.approve')
                                                        @if($comment->is_active->value != 1)
                                                            <form action="{{ route('dashboard.blogs.comments.approve', $comment->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="mdi mdi-check-circle-outline me-2"></i>
                                                                    {{ __('custom.words.approve') }}
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                    @can('blog.comment.reject')
                                                        @if($comment->is_active->value != 2)
                                                            <form action="{{ route('dashboard.blogs.comments.reject', $comment->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="mdi mdi-close-circle-outline me-2"></i>
                                                                    {{ __('custom.words.reject') }}
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                    @can('blog.comment.delete')
                                                        <form action="{{ route('dashboard.blogs.comments.destroy', $comment->id) }}"
                                                            method="POST"
                                                            class="d-inline"
                                                            onsubmit="return confirm('{{ __('custom.words.are_you_sure') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="mdi mdi-trash-can-outline me-2"></i>
                                                                {{ __('custom.words.delete') }}
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </div>
                                        @endcanany
                                    </div>
                                </div>

                                <!-- Comment Text -->
                                <div class="ms-5 ps-2">
                                    <p class="mb-2">{{ $comment->comment }}</p>

                                    @if($comment->email && !$comment->user)
                                        <small class="text-muted">
                                            <i class="mdi mdi-email-outline me-1"></i>
                                            {{ $comment->email }}
                                        </small>
                                    @endif
                                </div>

                                <!-- Replies -->
                                @if($comment->replies->count() > 0)
                                    <div class="ms-5 ps-4 mt-3">
                                        @foreach($comment->replies as $reply)
                                            <div class="reply-item mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div class="d-flex align-items-start">
                                                        <div class="avatar avatar-xs me-2">
                                                            @if($reply->user)
                                                                <img src="{{ $reply->user->imagePath }}" alt="{{ $reply->user->name }}" class="rounded-circle">
                                                            @else
                                                                <span class="avatar-initial rounded-circle bg-label-secondary" style="font-size: 10px;">
                                                                    {{ substr($reply->name ?? 'G', 0, 1) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1" style="font-size: 14px;">
                                                                {{ $reply->user ? $reply->user->name : $reply->name }}
                                                                @if(!$reply->user)
                                                                    <span class="badge bg-label-info" style="font-size: 10px;">{{ __('custom.words.guest') }}</span>
                                                                @endif
                                                            </h6>
                                                            <small class="text-muted" style="font-size: 12px;">
                                                                {{ $reply->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <span class="badge bg-label-{{ $reply->is_active->value == 1 ? 'success' : 'warning' }}" style="font-size: 10px;">
                                                            {{ $reply->is_active->lang() }}
                                                        </span>

                                                        @canany(['blog.comment.approve', 'blog.comment.reject', 'blog.comment.delete'])
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                                                    <i class="mdi mdi-dots-vertical"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    @can('blog.comment.approve')
                                                                        @if($reply->is_active->value != 1)
                                                                            <form action="{{ route('dashboard.blogs.comments.approve', $reply->id) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                <button type="submit" class="dropdown-item">
                                                                                    <i class="mdi mdi-check-circle-outline me-2"></i>
                                                                                    {{ __('custom.words.approve') }}
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    @endcan
                                                                    @can('blog.comment.reject')
                                                                        @if($reply->is_active->value != 2)
                                                                            <form action="{{ route('dashboard.blogs.comments.reject', $reply->id) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                <button type="submit" class="dropdown-item">
                                                                                    <i class="mdi mdi-close-circle-outline me-2"></i>
                                                                                    {{ __('custom.words.reject') }}
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    @endcan
                                                                    @can('blog.comment.delete')
                                                                        <form action="{{ route('dashboard.blogs.comments.destroy', $reply->id) }}"
                                                                            method="POST"
                                                                            class="d-inline"
                                                                            onsubmit="return confirm('{{ __('custom.words.are_you_sure') }}')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item text-danger">
                                                                                <i class="mdi mdi-trash-can-outline me-2"></i>
                                                                                {{ __('custom.words.delete') }}
                                                                            </button>
                                                                        </form>
                                                                    @endcan
                                                                </div>
                                                            </div>
                                                        @endcanany
                                                    </div>
                                                </div>
                                                <p class="mb-0 ms-4" style="font-size: 14px;">{{ $reply->comment }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="mdi mdi-comment-outline" style="font-size: 48px; color: #ccc;"></i>
                                <p class="text-muted mt-2">{{ __('custom.blog.no_comments_yet') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Blog Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('custom.blog.blog_info') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label text-muted">{{ __('custom.inputs.status') }}</label>
                            <div>
                                <span class="badge bg-label-{{ $blog->is_active->value == 1 ? 'success' : 'secondary' }}">
                                    {{ $blog->is_active->lang() }}
                                </span>
                            </div>
                        </div>

                        <!-- Creator -->
                        @if($blog->creator)
                            <div class="mb-3">
                                <label class="form-label text-muted">{{ __('custom.inputs.creator') }}</label>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="{{ $blog->creator->imagePath }}" alt="{{ $blog->creator->name }}" class="rounded-circle">
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $blog->creator->name }}</div>
                                        <small class="text-muted">{{ $blog->creator->email }}</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Published Date -->
                        <div class="mb-3">
                            <label class="form-label text-muted">{{ __('custom.inputs.published_at') }}</label>
                            <div>
                                @if($blog->published_at)
                                    <i class="mdi mdi-calendar me-1"></i>
                                    {{ $blog->published_at->format('F d, Y - h:i A') }}
                                @else
                                    <span class="badge bg-label-warning">{{ __('custom.words.not_published') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div class="mb-3">
                            <label class="form-label text-muted">{{ __('custom.words.created_at') }}</label>
                            <div>
                                <i class="mdi mdi-clock-outline me-1"></i>
                                {{ $blog->created_at->format('F d, Y - h:i A') }}
                            </div>
                        </div>

                        <!-- Updated Date -->
                        <div class="mb-3">
                            <label class="form-label text-muted">{{ __('custom.words.updated_at') }}</label>
                            <div>
                                <i class="mdi mdi-update me-1"></i>
                                {{ $blog->updated_at->format('F d, Y - h:i A') }}
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <label class="form-label text-muted">{{ __('custom.words.slug') }}</label>
                            <div>
                                <code>{{ $blog->slug }}</code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('custom.words.statistics') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <i class="mdi mdi-comment-multiple text-primary me-2"></i>
                                <span>{{ __('custom.blog.total_comments') }}</span>
                            </div>
                            <span class="badge bg-label-primary">{{ $blog->allComments->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <i class="mdi mdi-check-circle text-success me-2"></i>
                                <span>{{ __('custom.blog.approved_comments') }}</span>
                            </div>
                            <span class="badge bg-label-success">
                                {{ $blog->allComments->where('is_active.value', 1)->count() }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="mdi mdi-clock-alert text-warning me-2"></i>
                                <span>{{ __('custom.blog.pending_comments') }}</span>
                            </div>
                            <span class="badge bg-label-warning">
                                {{ $blog->allComments->where('is_active.value', 2)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    .blog-content {
        line-height: 1.8;
        font-size: 16px;
    }
    .blog-content img {
        max-width: 100%;
        height: auto;
    }
    .blog-content h1, .blog-content h2, .blog-content h3 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    .blog-content p {
        margin-bottom: 1rem;
    }
    .comment-item, .reply-item {
        position: relative;
    }
</style>
@endsection
