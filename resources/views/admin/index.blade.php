@extends('admin.layouts.app')

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

        <!-- Statistics Cards Row 1 - Users, Forms, Pages -->
        <div class="row g-4 mb-4">
            <!-- Users Card -->
            {{-- <div class="col-12 col-sm-6 col-lg-4">
                <div class="card analytics-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-label-primary">
                                <i class="mdi mdi-account-multiple"></i>
                            </div>
                            <div class="dropdown">
                                <button
                                    class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
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
                                <small class="d-block text-success">{{ $analytics['users']['active'] }}
                                    {{ __('custom.words.active') }}</small>
                                <small class="d-block text-muted">{{ $analytics['users']['inactive'] }}
                                    {{ __('custom.words.inactive') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Forms Card -->
            <div class="col-12 col-sm-6 col-lg-6">
                <div class="card analytics-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-label-warning">
                                <i class="mdi mdi-email-multiple-outline"></i>
                            </div>
                            <div class="dropdown">
                                <button
                                    class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
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
                                    <span class="badge bg-danger">{{ $analytics['forms']['unread'] }}
                                        {{ __('custom.words.unread') }}</span>
                                @else
                                    <small class="text-success">{{ __('custom.words.all_read') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pages Card -->
            <div class="col-12 col-sm-6 col-lg-6">
                <div class="card analytics-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon bg-label-info">
                                <i class="mdi mdi-file-document-multiple-outline"></i>
                            </div>
                            <div class="dropdown">
                                <button
                                    class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
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
                                <small class="d-block text-success">{{ $analytics['pages']['active'] }}
                                    {{ __('custom.words.active') }}</small>
                                <small class="d-block text-muted">{{ $analytics['pages']['inactive'] }}
                                    {{ __('custom.words.inactive') }}</small>
                            </div>
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
        </div>
    </div>
    <!-- / Content -->
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthlyCtx = document.getElementById('monthlyChart');
            if (monthlyCtx) {
                const monthlyData = @json($analytics['monthly_stats']);

                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyData.labels,
                        datasets: [
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
        });
    </script>
@endsection
