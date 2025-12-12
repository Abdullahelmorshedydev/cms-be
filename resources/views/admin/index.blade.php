@extends('dashboard.layouts.app')

@section('title', __('custom.titles.dashboard'))

@section('css')
<style>
    .analytics-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .analytics-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 24px;
    }

    .recent-item {
        transition: background-color 0.2s;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 8px;
    }

    .recent-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endsection

@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('custom.titles.dashboard') }}</h4>
            <p class="text-muted mb-0">{{ __('custom.words.welcome_back') }}, {{ auth()->user()->name }}!</p>
        </div>
        <div class="text-muted">
            <i class="mdi mdi-calendar-month"></i> {{ now()->format('l, F j, Y') }}
        </div>
    </div>

    <!-- Statistics Cards Row 1 - Users, Blogs, Forms, Pages -->
    <div class="row g-4 mb-4">
        <!-- Users Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-primary">
                            <i class="mdi mdi-account-multiple"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.users.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['users']['total']) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_users') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-success fw-medium">
                                <i class="mdi mdi-arrow-up"></i> {{ $analytics['users']['this_month'] }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.this_month') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-success">{{ $analytics['users']['active'] }} {{ __('custom.words.active') }}</small>
                            <small class="d-block text-muted">{{ $analytics['users']['inactive'] }} {{ __('custom.words.inactive') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blogs Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-success">
                            <i class="mdi mdi-post-outline"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.blogs.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['blogs']['total']) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_blogs') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-success fw-medium">
                                <i class="mdi mdi-arrow-up"></i> {{ $analytics['blogs']['this_month'] }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.this_month') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-success">{{ $analytics['blogs']['published'] }} {{ __('custom.words.published') }}</small>
                            <small class="d-block text-muted">{{ $analytics['blogs']['draft'] }} {{ __('custom.words.draft') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-warning">
                            <i class="mdi mdi-email-multiple-outline"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.forms.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['forms']['total']) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_forms') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-warning fw-medium">
                                <i class="mdi mdi-arrow-up"></i> {{ $analytics['forms']['this_month'] }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.this_month') }}</small>
                        </div>
                        <div class="text-end">
                            @if($analytics['forms']['unread'] > 0)
                                <span class="badge bg-danger">{{ $analytics['forms']['unread'] }} {{ __('custom.words.unread') }}</span>
                            @else
                                <small class="text-success">{{ __('custom.words.all_read') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pages Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-info">
                            <i class="mdi mdi-file-document-multiple-outline"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['pages']['total']) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_pages') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">{{ __('custom.words.active') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-success">{{ $analytics['pages']['active'] }} {{ __('custom.words.active') }}</small>
                            <small class="d-block text-muted">{{ $analytics['pages']['inactive'] }} {{ __('custom.words.inactive') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 2 - Students & Parents -->
    <div class="row g-4 mb-4">
        <!-- Students Card -->
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-info">
                            <i class="mdi mdi-school"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.students.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['students']['total']) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.student.total_students') }}</p>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.words.active') }}</small>
                                <span class="badge bg-success">{{ $analytics['students']['active'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.words.inactive') }}</small>
                                <span class="badge bg-secondary">{{ $analytics['students']['inactive'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.words.male') }}</small>
                                <span class="badge bg-primary">{{ $analytics['students']['male'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.words.female') }}</small>
                                <span class="badge bg-danger">{{ $analytics['students']['female'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.student.with_parents') }}</small>
                                <span class="badge bg-info">{{ $analytics['students']['with_parents'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.student.without_parents') }}</small>
                                <span class="badge bg-warning">{{ $analytics['students']['without_parents'] }}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-info fw-medium">
                            <i class="mdi mdi-arrow-up"></i> {{ $analytics['students']['this_month'] }}
                        </small>
                        <small class="text-muted">{{ __('custom.words.this_month') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parents Card -->
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-danger">
                            <i class="mdi mdi-account-supervisor"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.parents.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['parents']['total']) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.parent.total_parents') }}</p>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.words.active') }}</small>
                                <span class="badge bg-success">{{ $analytics['parents']['active'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.words.inactive') }}</small>
                                <span class="badge bg-secondary">{{ $analytics['parents']['inactive'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.parent.fathers') }}</small>
                                <span class="badge bg-primary">{{ $analytics['parents']['fathers'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.parent.mothers') }}</small>
                                <span class="badge bg-danger">{{ $analytics['parents']['mothers'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.parent.guardians') }}</small>
                                <span class="badge bg-warning">{{ $analytics['parents']['guardians'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ __('custom.parent.without_children') }}</small>
                                <span class="badge bg-info">{{ $analytics['parents']['without_children'] }}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-danger fw-medium">
                            <i class="mdi mdi-arrow-up"></i> {{ $analytics['parents']['this_month'] }}
                        </small>
                        <small class="text-muted">{{ __('custom.words.this_month') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 3 - Comments, Roles -->
    <div class="row g-4 mb-4">
        <!-- Comments Card -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-secondary">
                            <i class="mdi mdi-comment-multiple-outline"></i>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['comments']['total']) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_comments') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-success fw-medium">
                                <i class="mdi mdi-arrow-up"></i> {{ $analytics['comments']['this_month'] }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.this_month') }}</small>
                        </div>
                        <div class="text-end">
                            @if($analytics['comments']['pending'] > 0)
                                <span class="badge bg-warning">{{ $analytics['comments']['pending'] }} {{ __('custom.words.pending') }}</span>
                            @else
                                <small class="text-success">{{ __('custom.words.all_approved') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Card -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-dark">
                            <i class="mdi mdi-shield-account-outline"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.roles.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['roles']['total']) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_roles') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-info">{{ $analytics['users']['admins'] }} {{ __('custom.words.admins') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Card -->
        <div class="col-12 col-sm-12 col-lg-4">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ __('custom.words.quick_stats') }}</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">{{ __('custom.words.active_users') }}</span>
                        <span class="fw-bold text-primary">{{ $analytics['users']['active'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">{{ __('custom.words.published_blogs') }}</span>
                        <span class="fw-bold text-success">{{ $analytics['blogs']['published'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">{{ __('custom.words.unread_forms') }}</span>
                        <span class="fw-bold text-warning">{{ $analytics['forms']['unread'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">{{ __('custom.words.pending_comments') }}</span>
                        <span class="fw-bold text-secondary">{{ $analytics['comments']['pending'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <!-- Monthly Statistics Chart -->
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">{{ __('custom.words.monthly_statistics') }}</h5>
                    <small class="text-muted">{{ __('custom.words.last_6_months') }}</small>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms by Type Chart -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('custom.words.forms_by_type') }}</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="formsTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics Charts -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('custom.words.courses_by_category') }}</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="coursesCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('custom.words.courses_by_level') }}</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="coursesLevelChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('custom.words.lead_status_distribution') }}</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="leadsStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('custom.words.deals_stage_distribution') }}</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="dealsStageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('custom.words.expenses_vs_revenues') }}</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="financeComparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row g-4 mb-4">
        <!-- Recent Forms -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('custom.words.recent_forms') }}</h5>
                    <a href="{{ route('dashboard.forms.index') }}" class="btn btn-sm btn-text-primary">
                        {{ __('custom.words.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($analytics['recent_forms'] as $form)
                        <div class="recent-item">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <span class="badge-dot bg-{{ $form['is_read'] ? 'success' : 'warning' }}"></span>
                                        {{ $form['name'] }}
                                    </h6>
                                    <small class="text-muted d-block">{{ $form['email'] }}</small>
                                    <small class="text-muted">{{ $form['type'] }}</small>
                                </div>
                                <small class="text-muted">{{ $form['created_at'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-inbox mdi-48px mb-2"></i>
                            <p class="mb-0">{{ __('custom.words.no_recent_forms') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Blogs -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('custom.words.recent_blogs') }}</h5>
                    <a href="{{ route('dashboard.blogs.index') }}" class="btn btn-sm btn-text-primary">
                        {{ __('custom.words.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($analytics['recent_blogs'] as $blog)
                        <div class="recent-item">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($blog['title'], 40) }}</h6>
                                    <small class="text-muted d-block">{{ __('custom.words.by') }} {{ $blog['creator'] }}</small>
                                    <small class="text-muted">{{ $blog['published_at'] }}</small>
                                </div>
                                <small class="text-muted">{{ $blog['created_at'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-post-outline mdi-48px mb-2"></i>
                            <p class="mb-0">{{ __('custom.words.no_recent_blogs') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('custom.words.recent_users') }}</h5>
                    <a href="{{ route('dashboard.users.index') }}" class="btn btn-sm btn-text-primary">
                        {{ __('custom.words.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($analytics['recent_users'] as $user)
                        <div class="recent-item">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        {{ $user['name'] }}
                                        @if($user['is_admin'])
                                            <span class="badge badge-sm bg-primary">Admin</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted d-block">{{ $user['email'] }}</small>
                                </div>
                                <small class="text-muted">{{ $user['created_at'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-account-off-outline mdi-48px mb-2"></i>
                            <p class="mb-0">{{ __('custom.words.no_recent_users') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section - Students & Parents -->
    <div class="row g-4 mb-4">
        <!-- Recent Students -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="mdi mdi-school me-1"></i>
                        {{ __('custom.student.recent_students') }}
                    </h5>
                    <a href="{{ route('dashboard.students.index') }}" class="btn btn-sm btn-text-primary">
                        {{ __('custom.words.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($analytics['recent_students'] as $student)
                        <div class="recent-item">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <span class="badge-dot bg-{{ $student['is_active'] == 1 ? 'success' : 'danger' }}"></span>
                                        {{ $student['name'] }}
                                    </h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <small class="text-muted">{{ $student['email'] }}</small>
                                        @if($student['student_id'])
                                            <span class="badge badge-sm bg-info">ID: {{ $student['student_id'] }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-1">
                                        @if($student['grade'])
                                            <small class="text-muted">{{ __('custom.inputs.grade') }}: {{ $student['grade'] }}</small>
                                        @endif
                                        @if($student['class'])
                                            <small class="text-muted ms-2">{{ __('custom.inputs.class') }}: {{ $student['class'] }}</small>
                                        @endif
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="mdi mdi-account-supervisor"></i> {{ $student['parent_name'] }}
                                    </small>
                                </div>
                                <small class="text-muted">{{ $student['created_at'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-school-outline mdi-48px mb-2"></i>
                            <p class="mb-0">{{ __('custom.student.no_recent_students') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Parents -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="mdi mdi-account-supervisor me-1"></i>
                        {{ __('custom.parent.recent_parents') }}
                    </h5>
                    <a href="{{ route('dashboard.parents.index') }}" class="btn btn-sm btn-text-primary">
                        {{ __('custom.words.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($analytics['recent_parents'] as $parent)
                        <div class="recent-item">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <span class="badge-dot bg-{{ $parent['is_active'] == 1 ? 'success' : 'danger' }}"></span>
                                        {{ $parent['name'] }}
                                    </h6>
                                    <small class="text-muted d-block">{{ $parent['email'] }}</small>
                                    <div class="d-flex gap-2 flex-wrap mt-1">
                                        @if($parent['relationship_to_student'] != 'N/A')
                                            <span class="badge badge-sm bg-info">{{ ucfirst($parent['relationship_to_student']) }}</span>
                                        @endif
                                        <span class="badge badge-sm bg-primary">
                                            <i class="mdi mdi-account-multiple"></i> {{ $parent['children_count'] }} {{ __('custom.words.children') }}
                                        </span>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $parent['created_at'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-account-supervisor-outline mdi-48px mb-2"></i>
                            <p class="mb-0">{{ __('custom.parent.no_recent_parents') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- E-Learning Analytics Section -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3">
                <i class="mdi mdi-school me-2"></i>
                {{ __('custom.words.e_learning_analytics') }}
            </h5>
        </div>

        <!-- Courses Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-primary">
                            <i class="mdi mdi-book-open-variant"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.elearning.courses.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['courses']['total'] ?? 0) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_courses') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-success fw-medium">
                                <i class="mdi mdi-arrow-up"></i> {{ $analytics['courses']['published'] ?? 0 }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.published') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-info">{{ $analytics['courses']['featured'] ?? 0 }} {{ __('custom.words.featured') }}</small>
                            <small class="d-block text-muted">{{ $analytics['courses']['this_month'] ?? 0 }} {{ __('custom.words.this_month') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollments Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-success">
                            <i class="mdi mdi-account-plus"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.elearning.enrollments.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['enrollments']['total'] ?? 0) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_enrollments') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-success fw-medium">
                                <i class="mdi mdi-arrow-up"></i> {{ $analytics['enrollments']['active'] ?? 0 }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.active') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-primary">{{ $analytics['enrollments']['completed'] ?? 0 }} {{ __('custom.words.completed') }}</small>
                            <small class="d-block text-muted">{{ round($analytics['enrollments']['average_progress'] ?? 0, 1) }}% {{ __('custom.words.avg_progress') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quizzes Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-warning">
                            <i class="mdi mdi-help-circle"></i>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['quizzes']['total'] ?? 0) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_quizzes') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-success fw-medium">
                                <i class="mdi mdi-check"></i> {{ $analytics['quizzes']['published'] ?? 0 }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.published') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-info">{{ $analytics['quizzes']['total_attempts'] ?? 0 }} {{ __('custom.words.attempts') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-info">
                            <i class="mdi mdi-file-document-edit"></i>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['assignments']['total'] ?? 0) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_assignments') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-danger fw-medium">
                                <i class="mdi mdi-alert"></i> {{ $analytics['assignments']['overdue'] ?? 0 }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.overdue') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-primary">{{ $analytics['assignments']['total_submissions'] ?? 0 }} {{ __('custom.words.submissions') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CRM Analytics Section -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3">
                <i class="mdi mdi-account-group me-2"></i>
                {{ __('custom.words.crm_analytics') }}
            </h5>
        </div>

        <!-- Leads Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-primary">
                            <i class="mdi mdi-account-star"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.crm.leads.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['crm_leads']['total'] ?? 0) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_leads') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-success fw-medium">
                                <i class="mdi mdi-check-circle"></i> {{ $analytics['crm_leads']['converted'] ?? 0 }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.converted') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-info">{{ $analytics['crm_leads']['this_month'] ?? 0 }} {{ __('custom.words.this_month') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contacts Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-success">
                            <i class="mdi mdi-contacts"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.crm.contacts.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['crm_contacts']['total'] ?? 0) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_contacts') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-info fw-medium">
                                <i class="mdi mdi-arrow-up"></i> {{ $analytics['crm_contacts']['this_month'] ?? 0 }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.this_month') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deals Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-warning">
                            <i class="mdi mdi-handshake"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.crm.deals.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['crm_deals']['total'] ?? 0) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_deals') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-success fw-medium">
                                <i class="mdi mdi-trophy"></i> {{ $analytics['crm_deals']['won'] ?? 0 }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.won') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-primary">{{ number_format($analytics['crm_deals']['won_value'] ?? 0, 2) }}</small>
                            <small class="d-block text-muted">{{ __('custom.words.value') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card analytics-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-label-info">
                            <i class="mdi mdi-cash-multiple"></i>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('dashboard.crm.transactions.index') }}">
                                    <i class="mdi mdi-eye me-1"></i> {{ __('custom.words.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <h3 class="mb-1">{{ number_format($analytics['crm_transactions']['total'] ?? 0) }}</h3>
                    <p class="mb-3 text-muted">{{ __('custom.words.total_transactions') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-success fw-medium">
                                <i class="mdi mdi-check"></i> {{ $analytics['crm_transactions']['completed'] ?? 0 }}
                            </small>
                            <small class="text-muted">{{ __('custom.words.completed') }}</small>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-primary">{{ number_format($analytics['crm_transactions']['total_amount'] ?? 0, 2) }}</small>
                            <small class="d-block text-muted">{{ __('custom.words.amount') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HRMS & ERP Analytics Section -->
    <div class="row g-4 mb-4">
        <!-- HRMS Section -->
        <div class="col-12 col-lg-6">
            <h5 class="fw-bold mb-3">
                <i class="mdi mdi-account-tie me-2"></i>
                {{ __('custom.words.hrms_analytics') }}
            </h5>
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <div class="card analytics-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-label-primary">
                                    <i class="mdi mdi-account-group"></i>
                                </div>
                            </div>
                            <h3 class="mb-1">{{ number_format($analytics['employees']['total'] ?? 0) }}</h3>
                            <p class="mb-3 text-muted">{{ __('custom.words.total_employees') }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-success">{{ $analytics['employees']['active'] ?? 0 }} {{ __('custom.words.active') }}</small>
                                <small class="text-muted">{{ $analytics['employees']['this_month'] ?? 0 }} {{ __('custom.words.this_month') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="card analytics-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-label-success">
                                    <i class="mdi mdi-calendar-check"></i>
                                </div>
                            </div>
                            <h3 class="mb-1">{{ number_format($analytics['attendance']['this_month'] ?? 0) }}</h3>
                            <p class="mb-3 text-muted">{{ __('custom.words.attendance_this_month') }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-info">{{ $analytics['attendance']['today'] ?? 0 }} {{ __('custom.words.today') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ERP Section -->
        <div class="col-12 col-lg-6">
            <h5 class="fw-bold mb-3">
                <i class="mdi mdi-calculator me-2"></i>
                {{ __('custom.words.erp_analytics') }}
            </h5>
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <div class="card analytics-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-label-danger">
                                    <i class="mdi mdi-arrow-down"></i>
                                </div>
                            </div>
                            <h3 class="mb-1">{{ number_format($analytics['expenses']['total_amount'] ?? 0, 2) }}</h3>
                            <p class="mb-3 text-muted">{{ __('custom.words.total_expenses') }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-danger">{{ $analytics['expenses']['this_month_amount'] ?? 0 }} {{ __('custom.words.this_month') }}</small>
                                <small class="text-muted">{{ $analytics['expenses']['total'] ?? 0 }} {{ __('custom.words.records') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="card analytics-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="stat-icon bg-label-success">
                                    <i class="mdi mdi-arrow-up"></i>
                                </div>
                            </div>
                            <h3 class="mb-1">{{ number_format($analytics['revenues']['total_amount'] ?? 0, 2) }}</h3>
                            <p class="mb-3 text-muted">{{ __('custom.words.total_revenues') }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-success">{{ $analytics['revenues']['this_month_amount'] ?? 0 }} {{ __('custom.words.this_month') }}</small>
                                <small class="text-muted">{{ $analytics['revenues']['total'] ?? 0 }} {{ __('custom.words.records') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent E-Learning & CRM Activity -->
    <div class="row g-4 mb-4">
        <!-- Recent Courses -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="mdi mdi-book-open-variant me-1"></i>
                        {{ __('custom.words.recent_courses') }}
                    </h5>
                    <a href="{{ route('dashboard.elearning.courses.index') }}" class="btn btn-sm btn-text-primary">
                        {{ __('custom.words.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($analytics['recent_courses'] ?? [] as $course)
                        <div class="recent-item">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($course['title'], 40) }}</h6>
                                    <small class="text-muted d-block">{{ __('custom.words.category') }}: {{ $course['category'] }}</small>
                                    <small class="text-muted">{{ __('custom.words.instructor') }}: {{ $course['instructor'] }}</small>
                                </div>
                                <small class="text-muted">{{ $course['created_at'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-book-open-outline mdi-48px mb-2"></i>
                            <p class="mb-0">{{ __('custom.words.no_recent_courses') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Enrollments -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="mdi mdi-account-plus me-1"></i>
                        {{ __('custom.words.recent_enrollments') }}
                    </h5>
                    <a href="{{ route('dashboard.elearning.enrollments.index') }}" class="btn btn-sm btn-text-primary">
                        {{ __('custom.words.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($analytics['recent_enrollments'] ?? [] as $enrollment)
                        <div class="recent-item">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $enrollment['student_name'] }}</h6>
                                    <small class="text-muted d-block">{{ Str::limit($enrollment['course_title'], 35) }}</small>
                                    <small class="text-muted">{{ __('custom.words.progress') }}: {{ $enrollment['progress'] }}%</small>
                                </div>
                                <small class="text-muted">{{ $enrollment['created_at'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-account-plus-outline mdi-48px mb-2"></i>
                            <p class="mb-0">{{ __('custom.words.no_recent_enrollments') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Leads -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="mdi mdi-account-star me-1"></i>
                        {{ __('custom.words.recent_leads') }}
                    </h5>
                    <a href="{{ route('dashboard.crm.leads.index') }}" class="btn btn-sm btn-text-primary">
                        {{ __('custom.words.view_all') }}
                    </a>
                </div>
                <div class="card-body">
                    @forelse($analytics['recent_leads'] ?? [] as $lead)
                        <div class="recent-item">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $lead['name'] }}</h6>
                                    <small class="text-muted d-block">{{ $lead['email'] }}</small>
                                    <small class="text-muted">{{ __('custom.words.assigned_to') }}: {{ $lead['assigned_to'] }}</small>
                                </div>
                                <small class="text-muted">{{ $lead['created_at'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-account-star-outline mdi-48px mb-2"></i>
                            <p class="mb-0">{{ __('custom.words.no_recent_leads') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
<!-- / Content -->
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Monthly Statistics Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            const monthlyData = @json($analytics['monthly_stats']);

            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.labels,
                    datasets: [
                        {
                            label: '{{ __("custom.words.users") }}',
                            data: monthlyData.users,
                            borderColor: 'rgb(105, 108, 255)',
                            backgroundColor: 'rgba(105, 108, 255, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: '{{ __("custom.words.students") }}',
                            data: monthlyData.students,
                            borderColor: 'rgb(3, 195, 236)',
                            backgroundColor: 'rgba(3, 195, 236, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: '{{ __("custom.words.parents") }}',
                            data: monthlyData.parents,
                            borderColor: 'rgb(234, 84, 85)',
                            backgroundColor: 'rgba(234, 84, 85, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: '{{ __("custom.words.blogs") }}',
                            data: monthlyData.blogs,
                            borderColor: 'rgb(40, 199, 111)',
                            backgroundColor: 'rgba(40, 199, 111, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: '{{ __("custom.words.forms") }}',
                            data: monthlyData.forms,
                            borderColor: 'rgb(255, 171, 0)',
                            backgroundColor: 'rgba(255, 171, 0, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // Forms by Type Chart
        const formsTypeCtx = document.getElementById('formsTypeChart');
        if (formsTypeCtx) {
            const formsTypeData = @json($analytics['forms']['by_type']);
            const labels = Object.keys(formsTypeData);
            const data = Object.values(formsTypeData);

            const colors = [
                'rgba(105, 108, 255, 0.8)',
                'rgba(40, 199, 111, 0.8)',
                'rgba(255, 171, 0, 0.8)',
                'rgba(3, 195, 236, 0.8)',
                'rgba(234, 84, 85, 0.8)',
                'rgba(133, 146, 163, 0.8)'
            ];

            new Chart(formsTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }

        const palette = [
            '#696CFF', '#03C3EC', '#28C76F', '#FFAB00', '#FF4C51', '#8A8D93', '#A100FF', '#00CFE8', '#FF9F43', '#1E88E5'
        ];

        const formatLabel = label => {
            if (!label) return '';
            return label
                .toString()
                .replace(/[_-]+/g, ' ')
                .replace(/\b\w/g, char => char.toUpperCase());
        };

        // Courses by Category Chart
        const coursesCategoryCtx = document.getElementById('coursesCategoryChart');
        if (coursesCategoryCtx) {
            const coursesCategoryData = @json($analytics['courses']['by_category'] ?? []);
            const labels = Object.keys(coursesCategoryData);
            const data = Object.values(coursesCategoryData);

            new Chart(coursesCategoryCtx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: labels.map((_, idx) => palette[idx % palette.length]),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Courses by Level Chart
        const coursesLevelCtx = document.getElementById('coursesLevelChart');
        if (coursesLevelCtx) {
            const coursesLevelData = @json($analytics['courses']['by_level'] ?? []);
            const labels = Object.keys(coursesLevelData);
            const data = Object.values(coursesLevelData);

            new Chart(coursesLevelCtx, {
                type: 'polarArea',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: labels.map((_, idx) => palette[idx % palette.length]),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Leads Status Chart
        const leadsStatusCtx = document.getElementById('leadsStatusChart');
        if (leadsStatusCtx) {
            const leadsStatusData = @json($analytics['crm_leads']['by_status'] ?? []);
            const labels = Object.keys(leadsStatusData).map(formatLabel);
            const data = Object.values(leadsStatusData);

            new Chart(leadsStatusCtx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: labels.map((_, idx) => palette[idx % palette.length]),
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        }

        // Deals Stage Chart
        const dealsStageCtx = document.getElementById('dealsStageChart');
        if (dealsStageCtx) {
            const dealsStageData = @json($analytics['crm_deals']['by_stage'] ?? []);
            const labels = Object.keys(dealsStageData).map(formatLabel);
            const data = Object.values(dealsStageData);

            new Chart(dealsStageCtx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: labels.map((_, idx) => palette[idx % palette.length]),
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        }

        // Finance Comparison Chart
        const financeComparisonCtx = document.getElementById('financeComparisonChart');
        if (financeComparisonCtx) {
            const financeAmounts = @json([
                $analytics['expenses']['total_amount'] ?? 0,
                $analytics['revenues']['total_amount'] ?? 0,
            ]);

            new Chart(financeComparisonCtx, {
                type: 'bar',
                data: {
                    labels: [
                        '{{ __('custom.words.total_expenses') }}',
                        '{{ __('custom.words.total_revenues') }}'
                    ],
                    datasets: [{
                        data: financeAmounts,
                        backgroundColor: [palette[3], palette[2]],
                        borderRadius: 8,
                        maxBarThickness: 60
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: context => new Intl.NumberFormat().format(context.parsed.y ?? context.parsed)
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => new Intl.NumberFormat().format(value)
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
